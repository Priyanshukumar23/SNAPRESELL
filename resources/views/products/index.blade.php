@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <div class="flex justify-between items-center mb-4">
        <h2>Explore Marketplace</h2>
        <div class="flex gap-2">
            <a href="{{ route('cart.index') }}" class="btn btn-outline" style="position: relative;">
                <i class="ph ph-shopping-cart"></i> Cart
                @if(Auth::check() && \App\Models\CartItem::where('user_id', Auth::id())->count() > 0)
                    <span style="position: absolute; top: -5px; right: -5px; background: var(--primary-pink); color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;">
                        {{ \App\Models\CartItem::where('user_id', Auth::id())->count() }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <!-- Categories -->
    <div class="flex gap-2 mb-4 overflow-x-auto pb-2" style="scrollbar-width: none;">
        <a href="{{ route('products.index') }}" class="btn {{ !$category ? 'btn-primary' : 'btn-outline' }}" style="white-space: nowrap;">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat]) }}" class="btn {{ $category == $cat ? 'btn-primary' : 'btn-outline' }}" style="white-space: nowrap;">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="glass mb-4 p-4" style="padding: 1rem;">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            @if($category)
                <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <div class="form-group mb-0" style="flex: 2; min-width: 200px; margin: 0;">
                <label style="font-size: 0.875rem;">Search</label>
                <div style="position: relative;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search macbook, phones..." style="padding: 0.5rem 0.5rem 0.5rem 2rem; margin-top: 0.25rem;">
                </div>
            </div>
            <div class="form-group mb-0" style="flex: 1; min-width: 120px; margin: 0;">
                <label style="font-size: 0.875rem;">Min Price (Rs.)</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="0" style="padding: 0.5rem; margin-top: 0.25rem;">
            </div>
            <div class="form-group mb-0" style="flex: 1; min-width: 120px; margin: 0;">
                <label style="font-size: 0.875rem;">Max Price (Rs.)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max" style="padding: 0.5rem; margin-top: 0.25rem;">
            </div>
            <div class="form-group mb-0" style="flex: 1; min-width: 150px; margin: 0;">
                <label style="font-size: 0.875rem;">Sort</label>
                <select name="sort" class="form-control" style="padding: 0.5rem; margin-top: 0.25rem;">
                    <option value="">Latest</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Apply</button>
            </div>
            @if(request()->has('search') || request()->has('min_price') || request()->has('max_price') || request()->has('sort'))
            <div>
                <a href="{{ route('products.index', ['category' => $category]) }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">Clear</a>
            </div>
            @endif
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-3 gap-2">
        @forelse($products as $product)
            <div class="glass product-card" style="position: relative; {{ $product->stock <= 0 ? 'opacity: 0.6;' : '' }}">
                @if($product->stock <= 0)
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 10; display: flex; align-items: center; justify-content: center; border-radius: 12px; pointer-events: none;">
                        <span style="background: rgba(239, 68, 68, 0.9); color: white; padding: 0.5rem 1.5rem; font-weight: bold; font-size: 1.5rem; border-radius: 8px; transform: rotate(-15deg); border: 2px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">SOLD</span>
                    </div>
                @endif
                <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div style="height: 150px; background: rgba(255,255,255,0.05); border-radius: 12px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; overflow: hidden; transition: opacity 0.2s;" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            @if($product->category == 'Clothes') 👕
                            @elseif($product->category == 'Electronics') 💻
                            @elseif($product->category == 'Vehicles') 🚗
                            @elseif($product->category == 'Toys') 🧸
                            @elseif($product->category == 'Furniture') 🛋️
                            @else 📦 @endif
                        @endif
                    </div>
                    <h4 style="margin: 0; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-pink)'" onmouseout="this.style.color='inherit'">{{ $product->name }}</h4>
                </a>
                <p class="text-muted" style="font-size: 0.875rem; margin: 0.5rem 0;">{{ Str::limit($product->description, 50) }}</p>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem;">
                    <div class="flex justify-between items-center">
                        <span style="font-weight: bold; color: var(--primary-emerald); font-size: 1.2rem;">Rs. {{ number_format($product->price, 2) }}</span>
                        @if($product->has_bill) <span class="badge" title="Original Bill Included">📝 Bill</span> @endif
                    </div>
                    <div style="text-align: left; display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 0.75rem;"><i class="ph ph-coin"></i> Earn {{ floor($product->price * 0.05) }} Coins</span>
                        @if($product->stock > 0)
                            <span class="text-muted" style="font-size: 0.8rem;">{{ $product->stock }} piece(s) left</span>
                        @else
                            <span style="color: #ef4444; font-size: 0.8rem; font-weight: bold;">Out of Stock</span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-outline" style="width: 100%; padding: 0.5rem; font-size: 0.8rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}><i class="ph ph-shopping-cart"></i> Cart</button>
                        </form>
                        <div class="flex gap-1">
                            <a href="{{ route('messages', $product->seller_id) }}" class="btn btn-outline" style="flex: 1; padding: 0.5rem; font-size: 0.8rem; text-align: center;"><i class="ph ph-chat-circle"></i> Chat</a>
                            @if($product->seller && $product->seller->phone)
                                <a href="tel:{{ $product->seller->phone }}" class="btn btn-outline" style="flex: 1; padding: 0.5rem; font-size: 0.8rem; text-align: center; color: var(--primary-emerald); border-color: var(--primary-emerald);"><i class="ph ph-phone"></i> Call</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <form action="{{ route('cart.add') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="checkout" value="1">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.5rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}>Buy Now</button>
                        </form>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline" style="padding: 0.5rem; text-align: center; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                            <i class="ph ph-star"></i> Reviews
                        </a>
                    </div>

                    <div class="text-muted" style="font-size: 0.8rem; text-align: center; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                        Seller: <strong>{{ $product->seller->name ?? 'Unknown' }}</strong>
                        @if($product->seller && $product->seller->trust_points > 0)
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary-emerald); font-size: 0.7rem; margin-left: 0.25rem;" title="Verified Trust Score">
                                <i class="ph-fill ph-shield-check"></i> {{ $product->seller->trust_points }} Trust Score
                            </span>
                        @endif
                        @if($product->seller && $product->seller->location)
                            <br><i class="ph ph-map-pin"></i> {{ $product->seller->location }}
                        @endif
                        @if($product->seller && $product->seller->phone)
                            <br><i class="ph ph-phone"></i> {{ $product->seller->phone }}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="grid-span-3 text-center py-4 text-muted">
                No products found in this category.
            </div>
        @endforelse
    </div>
</div>

<style>
    .product-card {
        transition: transform 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
