@extends('layouts.app')

@section('content')
<div class="animate-fade-in text-center" style="padding: 4rem 0;">
    <h1 style="font-size: 3.5rem; background: linear-gradient(135deg, var(--primary-indigo), var(--primary-pink)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 1rem;">
        Resell Smarter. Live Greener.
    </h1>
    <p class="text-muted" style="font-size: 1.25rem; max-width: 600px; margin: 0 auto 2rem;">
        Join SnapResell to buy and sell premium second-hand items. Get AI-powered price recommendations and earn EcoPoints for every sustainable choice.
    </p>
    <div class="flex gap-2" style="justify-content: center; margin-bottom: 4rem;">
        <a href="/products" class="btn btn-primary" style="font-size: 1.1rem;">Start Exploring</a>
        <a href="/register" class="btn btn-outline" style="font-size: 1.1rem;">Join the Community</a>
    </div>

    <!-- Features Section -->
    <style>
        @media (min-width: 768px) {
            .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
    
    <h2 style="font-size: 2rem; margin-bottom: 2rem;">Why SnapResell?</h2>
    <div class="grid grid-cols-3 gap-2 text-left">
        <div class="glass product-card">
            <div style="font-size: 2rem; color: var(--primary-pink); margin-bottom: 1rem;">
                <i class="ph ph-magic-wand"></i>
            </div>
            <h3>AI Price Magic</h3>
            <p class="text-muted">No more guessing. Our AI analyzes market trends to recommend the perfect selling price for your items.</p>
        </div>
        <div class="glass product-card">
            <div style="font-size: 2rem; color: var(--primary-emerald); margin-bottom: 1rem;">
                <i class="ph ph-leaf"></i>
            </div>
            <h3>EcoPoints Rewards</h3>
            <p class="text-muted">Earn points for every second-hand purchase or sale. Redeem them for exclusive discounts and perks.</p>
        </div>
        <div class="glass product-card">
            <div style="font-size: 2rem; color: var(--primary-indigo); margin-bottom: 1rem;">
                <i class="ph ph-shield-check"></i>
            </div>
            <h3>Verified & Secure</h3>
            <p class="text-muted">Shop with confidence. We verify sellers, perform quality checks, and offer secure payment tracking.</p>
        </div>
    </div>
</div>

<!-- Featured Products Mockup -->
<div class="animate-fade-in" style="animation-delay: 0.2s;">
    <div class="flex justify-between items-center mb-4">
        <h2>Trending Finds</h2>
        <a href="/products" class="btn btn-outline" style="padding: 0.5rem 1rem;">View All</a>
    </div>
    <div class="grid grid-cols-4">
        <!-- Mock Product 1 -->
        <div class="glass product-card">
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400&h=300" alt="Sneakers" class="product-image">
            <div class="flex justify-between items-center mb-1">
                <span class="badge badge-ai"><i class="ph ph-sparkle"></i> AI Priced</span>
                <span class="badge badge-eco">+50 EcoPoints</span>
            </div>
            <h4>Nike Air Max</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="product-price">Rs. 85.00</span>
                <a href="/products/1" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px;">View</a>
            </div>
        </div>

        <!-- Mock Product 2 -->
        <div class="glass product-card">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=400&h=300" alt="Watch" class="product-image">
            <div class="flex justify-between items-center mb-1">
                <span class="badge badge-ai"><i class="ph ph-sparkle"></i> AI Priced</span>
                <span class="badge badge-eco">+120 EcoPoints</span>
            </div>
            <h4>Smart Watch Pro</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="product-price">Rs. 120.00</span>
                <a href="/products/2" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px;">View</a>
            </div>
        </div>

        <!-- Mock Product 3 -->
        <div class="glass product-card">
            <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&q=80&w=400&h=300" alt="Headphones" class="product-image">
            <div class="flex justify-between items-center mb-1">
                <span class="badge badge-ai"><i class="ph ph-sparkle"></i> AI Priced</span>
                <span class="badge badge-eco">+40 EcoPoints</span>
            </div>
            <h4>Sony Headphones</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="product-price">Rs. 60.00</span>
                <a href="/products/3" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px;">View</a>
            </div>
        </div>
        
        <!-- Mock Product 4 -->
        <div class="glass product-card">
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=400&h=300" alt="Headphones" class="product-image">
            <div class="flex justify-between items-center mb-1">
                <span class="badge badge-ai"><i class="ph ph-sparkle"></i> AI Priced</span>
                <span class="badge badge-eco">+80 EcoPoints</span>
            </div>
            <h4>Vintage Camera</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="product-price">Rs. 150.00</span>
                <a href="/products/4" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px;">View</a>
            </div>
        </div>
    </div>
</div>
@endsection
