<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Mock Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $messages = \App\Models\Message::where('receiver_id', Auth::id())
            ->with('sender')
            ->latest()
            ->take(2)
            ->get();

        $orders = \App\Models\Order::where('user_id', Auth::id())
            ->whereNotIn('status', ['Return Accepted', 'Cancelled', 'Returned', 'Return Declined'])
            ->with(['product', 'product.seller'])
            ->latest()
            ->get();

        $returnedOrders = \App\Models\Order::where('user_id', Auth::id())
            ->whereIn('status', ['Return Accepted', 'Cancelled', 'Returned', 'Return Declined'])
            ->with(['product', 'product.seller'])
            ->latest()
            ->get();
            
        $myProducts = [];
        $stats = [
            'totalItems' => 0,
            'totalSales' => 0,
            'totalEarned' => 0,
            'issues' => 0 // Returns/Replacements
        ];
        
        if (Auth::user()->role === 'seller') {
            $myProducts = \App\Models\Product::where('seller_id', Auth::id())
                ->with(['orders', 'orders.user'])
                ->latest()
                ->get();
                
            $stats['totalItems'] = $myProducts->count();
                
            foreach ($myProducts as $product) {
                foreach ($product->orders as $order) {
                    $orderStatus = strtolower($order->status);
                    
                    if ((str_contains($orderStatus, 'return') || str_contains($orderStatus, 'replace') || str_contains($orderStatus, 'cancel')) && !str_contains($orderStatus, 'return declined')) {
                        $stats['issues']++;
                    } else {
                        $stats['totalSales']++;
                        $stats['totalEarned'] += $order->amount ?? $product->price;
                    }
                }
            }
        } else {
            $allOrders = \App\Models\Order::where('user_id', Auth::id())->get();
            $stats['itemsReturned'] = $allOrders->filter(function($order) {
                $status = strtolower($order->status);
                return (str_contains($status, 'return') || str_contains($status, 'cancel')) && !str_contains($status, 'return declined');
            })->count();
            $stats['itemsBought'] = $allOrders->count() - $stats['itemsReturned'];
        }

        return view('dashboard', compact('messages', 'orders', 'returnedOrders', 'myProducts', 'stats'));
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);

    Route::get('/checkout', [\App\Http\Controllers\MarketplaceController::class, 'checkoutPage'])->name('checkout');

    Route::get('/payment/success', function (\Illuminate\Http\Request $request) {
        return view('payment.success', ['order_id' => session('order_id')]);
    })->name('payment.success');

    Route::get('/order/track/{id}', function ($id) {
        $order = \App\Models\Order::with(['product', 'product.seller'])->findOrFail($id);
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'seller') {
            abort(403);
        }
        return view('payment.track', compact('order'));
    })->name('order.track');

    Route::post('/order/{id}/cancel', function (\Illuminate\Http\Request $request, $id) {
        $order = \App\Models\Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'return_reason' => 'required|string|max:255'
        ]);

        if (now()->diffInDays($order->created_at) <= 7) {
            $order->update([
                'status' => 'Return Requested',
                'return_reason' => $request->return_reason
            ]);
        }
        
        return back()->with('success', 'Return/Cancellation requested successfully.');
    })->name('order.cancel');

    Route::post('/order/{id}/accept-return', function ($id) {
        $order = \App\Models\Order::whereHas('product', function($q) {
            $q->where('seller_id', Auth::id());
        })->with(['user', 'product'])->findOrFail($id);

        // 1. Remove Super Coins earned by buyer (5% of price)
        $coinsToRemove = floor(($order->amount ?? $order->product->price) * 0.05);
        if ($order->user) {
            $order->user->super_coins = max(0, $order->user->super_coins - $coinsToRemove);
            $order->user->save();
        }

        // 2. Restore Product Stock for Resale
        if ($order->product) {
            $order->product->increment('stock');
        }

        // 3. Add Review with Return Reason
        \App\Models\Review::create([
            'product_id' => $order->product_id,
            'user_id' => $order->user_id,
            'rating' => 1,
            'comment' => "Item Returned. Reason: " . ($order->return_reason ?? 'No reason provided'),
        ]);

        $order->update(['status' => 'Return Accepted']);
        return back()->with('success', 'Return request accepted, item relisted, and coins adjusted.');
    })->name('order.accept_return');

    Route::post('/order/{id}/decline-return', function (\Illuminate\Http\Request $request, $id) {
        $order = \App\Models\Order::whereHas('product', function($q) {
            $q->where('seller_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'decline_reason' => 'required|string|max:255'
        ]);

        $order->update([
            'status' => 'Return Declined',
            'decline_reason' => $request->decline_reason
        ]);
        
        return back()->with('success', 'Return request declined.');
    })->name('order.decline_return');
    
    Route::get('/messages/{id?}', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages');
    Route::post('/messages/send', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.send');
});

// Products Routes
Route::get('/products', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [\App\Http\Controllers\MarketplaceController::class, 'show'])->name('products.show');

// Marketplace Actions
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\MarketplaceController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [\App\Http\Controllers\MarketplaceController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [\App\Http\Controllers\MarketplaceController::class, 'cart'])->name('cart.index');
    Route::post('/checkout', [\App\Http\Controllers\MarketplaceController::class, 'checkout'])->name('checkout.process');
    Route::post('/products', [\App\Http\Controllers\MarketplaceController::class, 'store'])->name('products.store');
    Route::post('/products/{id}/review', [\App\Http\Controllers\MarketplaceController::class, 'storeReview'])->name('products.review.store');
    Route::delete('/products/{id}', [\App\Http\Controllers\MarketplaceController::class, 'destroy'])->name('products.destroy');
});

Route::get('/fix-data', function() {
    $orders = \App\Models\Order::whereNotIn('status', ['Return Requested', 'Return Accepted', 'Returned', 'Cancelled'])->get();
    foreach($orders as $order) {
        $product = \App\Models\Product::find($order->product_id);
        if ($product && $product->stock > 0) {
            $product->stock = 0;
            $product->save();
        }
        
        $user = \App\Models\User::find($order->user_id);
        if ($user && $user->super_coins == 0) {
            // Re-calculate the 5% coins they should have gotten
            $user->super_coins += floor(($order->amount ?? ($product ? $product->price : 0)) * 0.05);
            $user->save();
        }

        if ($product && $product->seller) {
            $product->seller->trust_points += 10;
            $product->seller->save();
        }
    }
    return 'Past Data Successfully Fixed! You can go back to the dashboard now.';
});


