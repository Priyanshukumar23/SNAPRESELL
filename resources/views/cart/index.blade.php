@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 600px; margin: 0 auto;">
    <h2 class="mb-4">Your Shopping Cart</h2>

    @if($cartItems->isEmpty())
        <div class="glass text-center py-5">
            <i class="ph ph-shopping-cart" style="font-size: 4rem; opacity: 0.2;"></i>
            <p class="mt-2">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-4">Browse Products</a>
        </div>
    @else
        <div class="glass">
            @foreach($cartItems as $item)
                <div class="flex justify-between items-center mb-4" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;' : '' }}">
                    <div class="flex items-center gap-2">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            @if($item->product->category == 'Clothes') 👕
                            @elseif($item->product->category == 'Electronics') 💻
                            @elseif($item->product->category == 'Vehicles') 🚗
                            @elseif($item->product->category == 'Toys') 🧸
                            @elseif($item->product->category == 'Furniture') 🛋️
                            @else 📦 @endif
                        </div>
                        <div>
                            <h4 style="margin: 0;">{{ $item->product->name }}</h4>
                            <span class="text-muted" style="font-size: 0.875rem;">Rs. {{ number_format($item->product->price, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span style="font-weight: bold; background: rgba(255,255,255,0.1); padding: 0.25rem 0.5rem; border-radius: 4px;">x{{ $item->quantity }}</span>
                        <form action="{{ route('cart.remove') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-outline" style="color: var(--primary-pink); border-color: var(--primary-pink); padding: 0.25rem 0.5rem; font-size: 0.75rem;" title="Remove one">
                                <i class="ph ph-minus"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="mt-4 pt-4" style="border-top: 2px solid var(--glass-border);">
                <div class="flex justify-between items-center mb-4">
                    <h3 style="margin: 0;">Total</h3>
                    <h3 style="margin: 0; color: var(--primary-emerald);">
                        Rs. {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 2) }}
                    </h3>
                </div>
                
                <a href="{{ route('checkout') }}" class="btn btn-primary" style="width: 100%; text-align: center; display: block;">Proceed to Checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
