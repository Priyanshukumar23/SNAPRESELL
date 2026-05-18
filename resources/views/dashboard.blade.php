@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        display: flex;
        gap: 2rem;
        min-height: 80vh;
        align-items: flex-start;
    }

    .dashboard-sidebar {
        width: 280px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 1.5rem;
        backdrop-filter: blur(12px);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        position: sticky;
        top: 2rem;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 14px;
        color: var(--color-text);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid transparent;
        font-weight: 500;
        opacity: 0.8;
    }

    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.05);
        opacity: 1;
        transform: translateX(5px);
    }

    .sidebar-item.active {
        background: linear-gradient(135deg, var(--primary-indigo), var(--primary-pink));
        color: white;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        opacity: 1;
    }

    .sidebar-item i {
        font-size: 1.25rem;
    }

    .dashboard-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .tab-content {
        display: none;
        animation: dashboardFadeIn 0.5s ease-out forwards;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes dashboardFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-card {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(236, 72, 153, 0.05));
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        margin-bottom: 1rem;
    }

    .stat-card-mini {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card-mini:hover {
        background: rgba(255, 255, 255, 0.04);
        transform: translateY(-5px);
    }
</style>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <div class="dashboard-sidebar">
        <div style="padding: 0 1rem 1.5rem 1rem; border-bottom: 1px solid var(--glass-border); margin-bottom: 1rem;">
            <h3 style="margin: 0; font-size: 1.25rem; color: var(--primary-indigo);">SnapResell</h3>
            <p class="text-muted" style="font-size: 0.8rem; margin: 0.25rem 0 0 0;">{{ Auth::user()->role === 'seller' ? 'Seller Center' : 'Buyer Hub' }}</p>
        </div>

        <div class="sidebar-item active" onclick="switchTab('overview', this)">
            <i class="ph ph-squares-four"></i>
            <span>Overview</span>
        </div>
        
        <div class="sidebar-item" onclick="switchTab('orders', this)">
            <i class="ph ph-shopping-bag"></i>
            <span>{{ Auth::user()->role === 'seller' ? 'My Listings' : 'My Orders' }}</span>
        </div>

        <div class="sidebar-item" onclick="switchTab('messages', this)">
            <i class="ph ph-chat-circle"></i>
            <span>Messages</span>
        </div>

        <div class="sidebar-item" onclick="switchTab('about', this)">
            <i class="ph ph-info"></i>
            <span>About</span>
        </div>

        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--glass-border);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%; border: none; background: none; color: #ef4444; opacity: 1;">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content area -->
    <div class="dashboard-main">
        
        <!-- OVERVIEW TAB -->
        <div id="overview" class="tab-content active">
            <div class="header-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 style="margin: 0;">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                        <p class="text-muted" style="margin: 0.5rem 0 0 0;">Here's what's happening with your account today.</p>
                    </div>
                    @if(Auth::user()->role === 'seller' && Auth::user()->trust_points > 0)
                        <div class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary-emerald); font-size: 1.1rem; padding: 0.75rem 1.25rem; border-radius: 16px;">
                            <i class="ph-fill ph-shield-check"></i> {{ Auth::user()->trust_points }} Trust Score
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid {{ Auth::user()->role === 'seller' ? 'grid-cols-4' : 'grid-cols-3' }} gap-2">
                @if(Auth::user()->role === 'seller')
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: var(--primary-pink); margin: 0;">{{ $stats['totalItems'] }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Total Items</p>
                    </div>
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: var(--primary-indigo); margin: 0;">{{ $stats['totalSales'] }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Total Sales</p>
                    </div>
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: #f59e0b; margin: 0;">{{ $stats['issues'] }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Returns</p>
                    </div>
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: var(--primary-emerald); margin: 0;">Rs. {{ number_format($stats['totalEarned'], 0) }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Total Earned</p>
                    </div>
                @else
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: var(--primary-indigo); margin: 0;">{{ $stats['itemsBought'] }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Items Bought</p>
                    </div>
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: #ef4444; margin: 0;">{{ $stats['itemsReturned'] }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Returns</p>
                    </div>
                    <div class="stat-card-mini">
                        <h3 style="font-size: 2rem; color: #f59e0b; margin: 0;"><i class="ph ph-coin"></i> {{ floor(Auth::user()->super_coins) }}</h3>
                        <p class="text-muted" style="margin: 0.25rem 0 0 0;">Super Coins</p>
                    </div>
                @endif
            </div>

            @if(Auth::user()->role === 'seller')
                <!-- Seller Listing Form (only on overview for quick access) -->
                <div class="glass mt-4">
                    <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="ph ph-plus-circle"></i> List New Item for Sale</h3>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-2 mt-2">
                        @csrf
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. iPhone 13" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="Clothes">Clothes</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Vehicles">Vehicles</option>
                                <option value="Toys">Toys</option>
                                <option value="Furniture">Furniture</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cost (Rs.)</label>
                            <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="stock" class="form-control" placeholder="1" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group grid-span-2">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Brief description" style="height: 80px;" required></textarea>
                        </div>
                        <div class="form-group grid-span-2 flex gap-4 mt-2">
                            <label class="flex items-center gap-1" style="cursor: pointer;">
                                <input type="checkbox" name="has_bill" value="1"> Original Bill
                            </label>
                            <label class="flex items-center gap-1" style="cursor: pointer;">
                                <input type="checkbox" name="has_replacement" value="1" checked> 7 Days Replacement
                            </label>
                        </div>
                        <div class="grid-span-2">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600;">Upload & List Item</button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Buyer Quick Welcome -->
                <div class="glass mt-4 text-center" style="padding: 3rem;">
                    <i class="ph ph-shopping-cart" style="font-size: 3rem; color: var(--primary-indigo); opacity: 0.3;"></i>
                    <h3>Looking for something new?</h3>
                    <p class="text-muted">Explore the latest listings from verified sellers across the marketplace.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-2" style="display: inline-block; padding: 0.75rem 2rem;">Explore Marketplace</a>
                </div>
            @endif
        </div>

        <!-- ORDERS / LISTINGS TAB -->
        <div id="orders" class="tab-content">
            @if(Auth::user()->role === 'seller')
                <h3>My Listed Items & Active Sales</h3>
                <div class="glass mt-2">
                    @forelse($myProducts as $product)
                        @if($product->orders->isNotEmpty())
                            @foreach($product->orders as $order)
                                <div class="flex justify-between items-center" style="{{ (!$loop->parent->last || !$loop->last) ? 'border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem; margin-bottom: 1.5rem;' : '' }}">
                                    <div class="flex items-center gap-4">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px; border: 1px solid var(--glass-border);">
                                        @else
                                            <div style="width: 60px; height: 60px; border-radius: 12px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border);">
                                                <i class="ph ph-image text-muted" style="font-size: 1.5rem;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 style="margin: 0; font-size: 1.1rem;">{{ $product->name }} <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">(#{{ $order->order_number }})</span></h4>
                                            <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
                                                <span style="font-weight: 600; color: var(--primary-emerald);">Rs. {{ number_format($product->price, 2) }}</span>
                                                @php
                                                    $orderStatus = strtolower($order->status);
                                                    $statusText = 'Already Bought'; $statusColor = 'var(--primary-pink)'; $statusBg = 'rgba(236, 72, 153, 0.1)';
                                                    if (str_contains($orderStatus, 'return requested')) { $statusText = 'Return Requested'; $statusColor = '#f59e0b'; $statusBg = 'rgba(245, 158, 11, 0.1)'; }
                                                    elseif (str_contains($orderStatus, 'return accepted')) { $statusText = 'Return Accepted'; $statusColor = 'var(--primary-emerald)'; $statusBg = 'rgba(16, 185, 129, 0.1)'; }
                                                    elseif (str_contains($orderStatus, 'cancelled')) { $statusText = 'Cancelled'; $statusColor = '#94a3b8'; $statusBg = 'rgba(148, 163, 184, 0.1)'; }
                                                @endphp
                                                <span class="badge" style="background: {{ $statusBg }}; color: {{ $statusColor }}; font-size: 0.75rem;">{{ $statusText }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(str_contains($orderStatus, 'return requested'))
                                            <form action="{{ route('order.accept_return', $order->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; background: var(--primary-emerald);">Accept Return</button>
                                            </form>
                                            <button class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem; color: #ef4444; border-color: #ef4444;" onclick="declineReturn({{ $order->id }})">Decline</button>
                                            <form id="decline-form-{{ $order->id }}" action="{{ route('order.decline_return', $order->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                <input type="hidden" name="decline_reason" id="decline-reason-{{ $order->id }}">
                                            </form>
                                        @endif
                                        <a href="{{ route('messages', $order->user_id) }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;"><i class="ph ph-chat-circle"></i> Chat</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex justify-between items-center" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; margin-bottom: 1rem;' : '' }}">
                                <div class="flex items-center gap-4">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div style="width: 50px; height: 50px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border);"><i class="ph ph-image text-muted"></i></div>
                                    @endif
                                    <div>
                                        <h4 style="margin: 0;">{{ $product->name }}</h4>
                                        <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
                                            <span style="font-weight: 600; color: var(--primary-emerald);">Rs. {{ number_format($product->price, 2) }}</span>
                                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary-indigo); font-size: 0.75rem;">Listed ({{ $product->stock }} left)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this listing?');" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline" style="color: #ef4444; border-color: #ef4444; padding: 0.5rem 1rem; font-size: 0.8rem;"><i class="ph ph-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-muted" style="padding: 2rem;">No items listed yet. Start selling today!</div>
                    @endforelse
                </div>
            @else
                <h3>Active Orders</h3>
                <div class="glass mt-2">
                    @forelse($orders as $order)
                        <div style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border); padding-bottom: 1.5rem; margin-bottom: 1.5rem;' : '' }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-4">
                                    @if($order->product->image)
                                        <img src="{{ Storage::url($order->product->image) }}" alt="{{ $order->product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px; border: 1px solid var(--glass-border);">
                                    @else
                                        <div style="width: 60px; height: 60px; border-radius: 12px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border);"><i class="ph ph-image text-muted"></i></div>
                                    @endif
                                    <div>
                                        <h4 style="margin: 0; font-size: 1.1rem;">{{ $order->product->name }}</h4>
                                        <span class="badge mt-1" style="background: rgba(99, 102, 241, 0.1); color: var(--primary-indigo);">{{ ucfirst($order->status) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('order.track', $order->id) }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;"><i class="ph ph-map-pin-line"></i> Track</a>
                                @if($order->product->seller && $order->product->seller->phone)
                                    <a href="tel:{{ $order->product->seller->phone }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem; color: var(--primary-emerald); border-color: var(--primary-emerald);"><i class="ph ph-phone"></i> Call Seller</a>
                                @endif
                                @if($order->product->seller)
                                    <a href="{{ route('messages', $order->product->seller->id) }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;"><i class="ph ph-chat-circle"></i> Message</a>
                                @endif
                                @if(now()->diffInDays($order->created_at) <= 7 && !in_array(strtolower($order->status), ['cancelled', 'returned', 'return requested', 'return accepted', 'return declined']))
                                    <button type="button" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #ef4444; border-color: #ef4444;" onclick="requestReturn({{ $order->id }})"><i class="ph ph-arrow-u-up-left"></i> Return</button>
                                    <form id="cancel-form-{{ $order->id }}" action="{{ route('order.cancel', $order->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="return_reason" id="return-reason-{{ $order->id }}">
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted" style="padding: 2rem;">No active orders.</div>
                    @endforelse
                </div>

                @if(isset($returnedOrders) && $returnedOrders->count() > 0)
                    <h3 class="mt-4">Order History</h3>
                    <div class="glass mt-2">
                        @foreach($returnedOrders as $order)
                            <div style="{{ !$loop->last ? 'border-bottom: 1px solid var(--glass-border); padding-bottom: 1.25rem; margin-bottom: 1.25rem;' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-4">
                                        @if($order->product->image)
                                            <img src="{{ Storage::url($order->product->image) }}" alt="{{ $order->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 1px solid var(--glass-border);">
                                        @else
                                            <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border);"><i class="ph ph-image text-muted"></i></div>
                                        @endif
                                        <div>
                                            <h4 style="margin: 0;">{{ $order->product->name }}</h4>
                                            <span class="badge mt-1" style="background: rgba(148, 163, 184, 0.1); color: #64748b;">{{ ucfirst($order->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('order.track', $order->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="ph ph-map-pin-line"></i> View Tracking</a>
                                    @if($order->product->seller)
                                        <a href="{{ route('messages', $order->product->seller->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="ph ph-chat-circle"></i> Chat</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        <!-- MESSAGES TAB -->
        <div id="messages" class="tab-content">
            <h3>Recent Messages</h3>
            <div class="glass mt-2">
                @forelse($messages as $msg)
                    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; margin-bottom: 1rem;">
                        <div class="flex justify-between">
                            <strong>{{ $msg->sender->name }}</strong>
                            <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                        </div>
                        <p style="margin: 0.5rem 0;">{{ $msg->message }}</p>
                        <a href="{{ route('messages', $msg->sender_id) }}" class="text-primary" style="font-size: 0.875rem;">Reply</a>
                    </div>
                @empty
                    <div class="text-center text-muted" style="padding: 2rem;">No recent messages.</div>
                @endforelse
                
                <div class="text-center mt-4">
                    <a href="{{ route('messages') }}" class="btn btn-primary" style="display: inline-block;">View All Messages</a>
                </div>
            </div>
        </div>

        <!-- ABOUT TAB -->
        <div id="about" class="tab-content">
            <div class="header-card">
                <h2 style="margin: 0;">About SnapResell 🚀</h2>
                <p class="text-muted" style="margin: 0.5rem 0 0 0;">Your ultimate peer-to-peer marketplace platform.</p>
            </div>
            
            <div class="glass mt-2" style="padding: 2rem;">
                <p style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem; text-align: center;">SnapResell is designed to provide a seamless buying and selling experience. Whether you're clearing out your closet as a seller or hunting for the best deals as a buyer, we've got you covered.</p>
                
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div style="background: rgba(99, 102, 241, 0.05); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(99, 102, 241, 0.2);">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="ph-fill ph-shopping-cart" style="font-size: 2rem; color: var(--primary-indigo);"></i>
                            <h3 style="margin: 0;">For Buyers</h3>
                        </div>
                        <ul style="list-style-type: disc; padding-left: 1.5rem; color: var(--color-text); opacity: 0.9; line-height: 1.8;">
                            <li><strong>Super Coins:</strong> Earn 5% back in Super Coins on every purchase to use later.</li>
                            <li><strong>Order Tracking:</strong> Track your orders in real-time from the "My Orders" tab.</li>
                            <li><strong>Direct Chat:</strong> Message sellers directly to ask questions about products.</li>
                            <li><strong>Easy Returns:</strong> Request returns within 7 days hassle-free.</li>
                        </ul>
                    </div>
                    
                    <div style="background: rgba(236, 72, 153, 0.05); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(236, 72, 153, 0.2);">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="ph-fill ph-storefront" style="font-size: 2rem; color: var(--primary-pink);"></i>
                            <h3 style="margin: 0;">For Sellers</h3>
                        </div>
                        <ul style="list-style-type: disc; padding-left: 1.5rem; color: var(--color-text); opacity: 0.9; line-height: 1.8;">
                            <li><strong>Trust Points:</strong> Earn 10 Trust Points for every successful order. Higher points mean more buyer trust!</li>
                            <li><strong>Manage Listings:</strong> Add, edit, and track your active product listings easily.</li>
                            <li><strong>Handle Returns:</strong> Accept or decline return requests directly from your dashboard.</li>
                            <li><strong>Instant Communication:</strong> Chat with potential buyers to close deals faster.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Reason Modal -->
<div id="reasonModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="glass" style="width: 100%; max-width: 400px; padding: 2rem; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div class="flex items-center gap-2 mb-2">
            <i class="ph-fill ph-warning-circle" style="color: var(--primary-pink); font-size: 1.5rem;"></i>
            <h3 id="reasonModalTitle" style="margin: 0;">Provide Reason</h3>
        </div>
        <p id="reasonModalDesc" class="text-muted" style="font-size: 0.875rem; margin-bottom: 1rem;">Please specify your reason below.</p>
        <textarea id="reasonModalInput" class="form-control" rows="3" placeholder="Type your reason here..." style="width: 100%; margin-bottom: 1rem; border-radius: 8px;"></textarea>
        <div class="flex gap-2 justify-end">
            <button type="button" class="btn btn-outline" onclick="closeReasonModal()" style="flex: 1;">Cancel</button>
            <button type="button" class="btn btn-primary" id="reasonModalSubmit" style="flex: 1;">Submit</button>
        </div>
    </div>
</div>

<script>
    let activeReasonId = null;
    let activeReasonType = null; // 'return' or 'decline'

    function switchTab(tabId, element) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Deactivate all sidebar items
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Show selected tab
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.add('active');
        }
        
        // Activate clicked sidebar item
        if (element) {
            element.classList.add('active');
        }

        // Scroll to top of dashboard-main if needed
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function openReasonModal(id, type) {
        activeReasonId = id;
        activeReasonType = type;
        
        document.getElementById('reasonModal').style.display = 'flex';
        document.getElementById('reasonModalInput').value = '';
        
        setTimeout(() => document.getElementById('reasonModalInput').focus(), 100);
        
        if (type === 'return') {
            document.getElementById('reasonModalTitle').innerText = 'Request Return';
            document.getElementById('reasonModalDesc').innerText = 'Please provide a valid reason for returning or cancelling this item:';
        } else {
            document.getElementById('reasonModalTitle').innerText = 'Decline Return';
            document.getElementById('reasonModalDesc').innerText = 'Please provide a reason for declining the buyer\'s return request:';
        }
    }

    function closeReasonModal() {
        document.getElementById('reasonModal').style.display = 'none';
        activeReasonId = null;
        activeReasonType = null;
    }

    document.getElementById('reasonModalSubmit').addEventListener('click', function() {
        let reason = document.getElementById('reasonModalInput').value;
        if (reason && reason.trim() !== '') {
            if (activeReasonType === 'return') {
                document.getElementById('return-reason-' + activeReasonId).value = reason;
                document.getElementById('cancel-form-' + activeReasonId).submit();
            } else if (activeReasonType === 'decline') {
                document.getElementById('decline-reason-' + activeReasonId).value = reason;
                document.getElementById('decline-form-' + activeReasonId).submit();
            }
        } else {
            document.getElementById('reasonModalInput').style.borderColor = '#ef4444';
            document.getElementById('reasonModalInput').placeholder = 'A reason is required...';
        }
    });

    function requestReturn(id) {
        openReasonModal(id, 'return');
    }

    function declineReturn(id) {
        openReasonModal(id, 'decline');
    }
</script>
@endsection
