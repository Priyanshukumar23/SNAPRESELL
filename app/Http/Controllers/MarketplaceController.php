<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sort = $request->query('sort');
        $search = $request->query('search');

        $query = Product::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->get();
        $categories = ['Clothes', 'Electronics', 'Vehicles', 'Toys', 'Furniture'];

        return view('products.index', compact('products', 'categories', 'category', 'search'));
    }

    public function show($id)
    {
        $product = Product::with(['seller', 'reviews.user', 'orders.user'])->findOrFail($id);
        
        $canReview = false;
        if (Auth::check()) {
            $hasPurchased = $product->orders->where('user_id', Auth::id())
                                            ->whereNotIn('status', ['Cancelled', 'Return Accepted'])
                                            ->isNotEmpty();
            $hasReviewed = $product->reviews->where('user_id', Auth::id())->isNotEmpty();
            $canReview = $hasPurchased && !$hasReviewed;
        }

        return view('products.show', compact('product', 'canReview'));
    }

    public function addToCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $product = Product::findOrFail($request->product_id);
        
        $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;

        if ($currentQuantity >= $product->stock) {
            return back()->with('error', 'Cannot add more. Only ' . $product->stock . ' left in stock.');
        }

        CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['quantity' => \DB::raw('quantity + 1')]
        );

        if ($request->has('checkout')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', 'Item added to cart!');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate(['cart_item_id' => 'required|exists:cart_items,id']);
        
        $item = CartItem::where('user_id', Auth::id())->where('id', $request->cart_item_id)->firstOrFail();
        
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function cart()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function checkoutPage()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $platformFee = 2.50;
        $superCoins = Auth::user()->super_coins;
        return view('payment.checkout', compact('cartItems', 'total', 'platformFee', 'superCoins'));
    }

    public function checkout(Request $request)
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $platformFee = 2.50;
        $grandTotal = $total + $platformFee;

        $user = Auth::user();
        $coinsToUse = 0;

        if ($request->has('use_coins') && $request->use_coins > 0) {
            $coinsToUse = min($request->use_coins, $user->super_coins, $grandTotal);
            $user->super_coins -= $coinsToUse;
        }

        $lastOrder = null;
        $coinsEarned = 0;

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', 'Not enough stock for ' . $item->product->name);
            }

            $lastOrder = Order::create([
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'status' => 'processing',
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'amount' => ($item->product->price * $item->quantity) // Original item price saved
            ]);
            
            // Deduct stock
            $item->product->decrement('stock', $item->quantity);

            // Earn 5% of item value as Super Coins
            $coinsEarned += floor(($item->product->price * $item->quantity) * 0.05);

            // Award Trust Points to Seller (10 points per item piece sold)
            if ($item->product->seller) {
                $item->product->seller->increment('trust_points', 10 * $item->quantity);
            }
        }

        // Add newly earned coins
        $user->super_coins += $coinsEarned;
        $user->save();

        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('payment.success')->with([
            'success' => 'Purchase successful! You used ' . $coinsToUse . ' Super Coins and earned ' . $coinsEarned . ' new Super Coins.',
            'order_id' => $lastOrder ? $lastOrder->id : null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:1',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'has_bill' => 'nullable|boolean',
            'has_replacement' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock ?? 1,
            'category' => $request->category,
            'image' => $imagePath,
            'seller_id' => Auth::id(),
            'has_bill' => $request->has('has_bill'),
            'has_replacement' => $request->has('has_replacement'),
        ]);

        return back()->with('success', 'Product listed successfully!');
    }

    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        $product = Product::findOrFail($id);
        
        $hasReviewed = \App\Models\Review::where('product_id', $id)->where('user_id', Auth::id())->exists();
        if ($hasReviewed) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $hasPurchased = $product->orders()->where('user_id', Auth::id())
                                          ->whereNotIn('status', ['Cancelled', 'Return Accepted', 'Return Declined', 'Return Requested'])
                                          ->exists();
                                          
        if (!$hasPurchased) {
            return back()->with('error', 'You can only review products you have bought.');
        }

        \App\Models\Review::create([
            'product_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
