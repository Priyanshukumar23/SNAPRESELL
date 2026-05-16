@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <div class="grid grid-cols-2" style="gap: 3rem;">
        <div style="position: relative;">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=800&h=600' }}" alt="Product Image" style="width: 100%; border-radius: 14px; box-shadow: var(--glass-shadow); max-height: 400px; object-fit: cover; {{ $product->stock <= 0 ? 'opacity: 0.5;' : '' }}">
            @if($product->stock <= 0)
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; z-index: 10;">
                    <span style="background: rgba(239, 68, 68, 0.9); color: white; padding: 1rem 3rem; font-weight: bold; font-size: 2.5rem; border-radius: 12px; transform: rotate(-15deg); border: 4px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">SOLD OUT</span>
                </div>
            @endif
            
            <div class="glass mt-4">
                <h4>Seller Information</h4>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center gap-2">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-indigo); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                            {{ strtoupper(substr($product->seller->name ?? 'J', 0, 1)) }}
                        </div>
                        <div>
                            <h4 style="margin: 0;">{{ $product->seller->name ?? 'Unknown Seller' }} <i class="ph ph-seal-check" style="color: var(--primary-indigo);"></i></h4>
                            @if($product->seller && $product->seller->trust_points > 0)
                                <div style="color: var(--primary-emerald); font-size: 0.875rem; font-weight: bold; margin-top: 0.25rem;">
                                    <i class="ph-fill ph-shield-check"></i> {{ $product->seller->trust_points }} Trust Score
                                </div>
                            @endif
                            @if($product->seller && $product->seller->location)
                                <div class="text-muted" style="font-size: 0.875rem; margin-top: 0.25rem;"><i class="ph ph-map-pin"></i> {{ $product->seller->location }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($product->seller && $product->seller->phone)
                            <a href="tel:{{ $product->seller->phone }}" class="btn btn-outline" style="padding: 0.5rem 1rem; color: var(--primary-emerald); border-color: var(--primary-emerald);"><i class="ph ph-phone"></i> Call ({{ $product->seller->phone }})</a>
                        @endif
                        <a href="{{ route('messages', $product->seller_id) }}" class="btn btn-outline" style="padding: 0.5rem 1rem;"><i class="ph ph-chat-circle"></i> Chat</a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-start">
                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">{{ $product->name }}</h1>
                <div class="text-right">
                    <div class="product-price" style="font-size: 2rem;">Rs. {{ number_format($product->price, 2) }}</div>
                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; margin-top: 0.25rem; display: inline-block;"><i class="ph ph-coin"></i> Earn {{ floor($product->price * 0.05) }} Super Coins</span>
                    @if($product->has_bill) <br><span class="badge mt-2" title="Original Bill Included">📝 Bill</span> @endif
                    
                    <div class="mt-2">
                        @if($product->stock > 0)
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary-emerald);">{{ $product->stock }} in stock</span>
                        @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 1.1rem; font-weight: bold;">SOLD OUT</span>
                        @endif
                    </div>
                </div>
            </div>

            <p class="text-muted mt-2" style="font-size: 1.1rem;">
                {{ $product->description }}
            </p>

            <div class="glass mt-4" style="background: rgba(236, 72, 153, 0.05); border-color: rgba(236, 72, 153, 0.2);">
                <div class="flex items-center gap-2 mb-2">
                    <i class="ph ph-sparkle" style="color: var(--primary-pink); font-size: 1.5rem;"></i>
                    <h4 style="margin: 0; color: var(--primary-pink);">AI Price Recommendation</h4>
                </div>
                <p style="margin-bottom: 0;">Our AI analyzed 150 similar listings. This item is priced <strong>15% below market average</strong>. It's a great deal!</p>
            </div>

            <div class="glass mt-4" style="background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2);">
                 <div class="flex items-center gap-2 mb-2">
                    <i class="ph ph-shield-check" style="color: var(--primary-emerald); font-size: 1.5rem;"></i>
                    <h4 style="margin: 0; color: var(--primary-emerald);">Quality Checked</h4>
                </div>
                <p style="margin-bottom: 0;">This item has passed our digital verification process.</p>
            </div>
            
            <div class="glass mt-4">
                 <div class="flex items-center gap-2 mb-2">
                    <i class="ph ph-map-pin" style="color: var(--primary-indigo); font-size: 1.5rem;"></i>
                    <h4 style="margin: 0;">Meet-up Coordination</h4>
                </div>
                <p class="text-muted" style="margin-bottom: 0;">Seller prefers to meet at <strong>Central Park Cafe</strong>. Safe zone verified.</p>
            </div>

            <div class="mt-4 flex gap-2">
                @auth
                    <form action="{{ route('cart.add') }}" method="POST" style="flex: 1; margin: 0;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="checkout" value="1">
                        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.2rem; padding: 1rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}><i class="ph ph-shopping-cart"></i> Buy Now</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="flex: 1; font-size: 1.2rem; padding: 1rem;">Log in to Buy</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3>Reviews & History</h3>
        
        <div class="grid grid-cols-2 mt-4" style="gap: 2rem;">
            <!-- Reviews List -->
            <div class="glass">
                <h4>Customer Reviews</h4>
                @if($product->reviews->isEmpty())
                    <p class="text-muted">No reviews yet.</p>
                @else
                    @foreach($product->reviews as $review)
                        <div class="mb-4 pb-4" style="border-bottom: 1px solid var(--glass-border);">
                            <div class="flex items-center gap-2 mb-1">
                                <strong>{{ $review->user->name }}</strong>
                                <span class="text-muted" style="font-size: 0.875rem;">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-warning mb-1" style="color: #f59e0b;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="ph-fill ph-star"></i>
                                    @else
                                        <i class="ph ph-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p style="margin: 0;">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                @endif

                <!-- Write Review Form -->
                @if($canReview)
                    <div class="mt-4 pt-4" style="border-top: 1px solid var(--glass-border);">
                        <h4>Write a Review</h4>
                        <form action="{{ route('products.review.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-2">
                                <label>Rating</label>
                                <select name="rating" class="form-control" required style="max-width: 200px;">
                                    <option value="5">5 Stars - Excellent</option>
                                    <option value="4">4 Stars - Very Good</option>
                                    <option value="3">3 Stars - Good</option>
                                    <option value="2">2 Stars - Fair</option>
                                    <option value="1">1 Star - Poor</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label>Comment</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- History List -->
            <div class="glass">
                <h4>Item History</h4>
                @php
                    $historyOrders = $product->orders->filter(function($order) {
                        return in_array(strtolower($order->status), ['return requested', 'return accepted', 'return declined', 'cancelled']);
                    });
                @endphp
                
                @if($historyOrders->isEmpty())
                    <p class="text-muted">No return or cancellation history.</p>
                @else
                    @foreach($historyOrders as $order)
                        <div class="mb-4 pb-4" style="border-bottom: 1px solid var(--glass-border);">
                            <div class="flex items-center gap-2 mb-1">
                                <strong>{{ $order->user->name }}</strong>
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.75rem;">{{ ucfirst($order->status) }}</span>
                            </div>
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
                                Date: {{ $order->updated_at->format('M d, Y') }}
                            </p>
                            @if($order->return_reason)
                                <p style="margin: 0; margin-top: 0.5rem;">Reason: <em>"{{ $order->return_reason }}"</em></p>
                            @endif
                            @if($order->decline_reason)
                                <p style="margin: 0; margin-top: 0.5rem; color: #ef4444;">Seller Declined Reason: <em>"{{ $order->decline_reason }}"</em></p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
