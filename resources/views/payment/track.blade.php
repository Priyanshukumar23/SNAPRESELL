@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 800px; margin: 2rem auto;">
    <div class="flex justify-between items-center mb-4">
        <h2>Track Order #{{ $order->order_number }}</h2>
        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary-indigo); font-size: 1rem;">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3" style="gap: 2rem;">
        <!-- Order Details -->
        <div class="glass" style="grid-column: span 1; align-self: start;">
            <h3>Order Details</h3>
            <div class="mt-4">
                @if($order->product->image)
                    <img src="{{ Storage::url($order->product->image) }}" alt="Product" style="width: 100%; border-radius: 8px; margin-bottom: 1rem; height: 200px; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 200px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border); margin-bottom: 1rem;">
                        <i class="ph ph-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <h4>{{ $order->product->name }}</h4>
                <p class="text-muted" style="margin-bottom: 0.5rem;">Seller: {{ $order->product->seller->name }}</p>
                @if($order->product->seller->location)
                    <p class="text-muted" style="margin-bottom: 0.5rem; font-size: 0.875rem;"><i class="ph ph-map-pin"></i> {{ $order->product->seller->location }}</p>
                @endif
                <div class="flex justify-between font-bold" style="font-size: 1.2rem; border-top: 1px solid var(--glass-border); padding-top: 1rem;">
                    <span>Total Paid</span>
                    <span style="color: var(--primary-indigo);">Rs. {{ number_format($order->amount, 2) }}</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('messages', $order->product->seller->id) }}" class="btn btn-outline" style="width: 100%;"><i class="ph ph-chat-circle"></i> Contact Seller</a>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="glass" style="grid-column: span 2;">
            <h3 style="margin-bottom: 2rem;">Shipping Status</h3>

            <div style="position: relative; padding-left: 2rem;">
                <!-- Timeline Line -->
                <div style="position: absolute; left: 11px; top: 10px; bottom: 10px; width: 2px; background: var(--glass-border);"></div>
                <div style="position: absolute; left: 11px; top: 10px; height: 50%; width: 2px; background: var(--primary-emerald); z-index: 1;"></div>

                <!-- Step 1 -->
                <div style="position: relative; margin-bottom: 2rem;">
                    <div style="position: absolute; left: -2rem; top: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--primary-emerald); color: white; display: flex; align-items: center; justify-content: center; z-index: 2;">
                        <i class="ph ph-check" style="font-size: 0.8rem;"></i>
                    </div>
                    <h4 style="margin: 0; color: var(--primary-emerald);">Order Confirmed</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin: 0;">Payment successful. Seller notified.</p>
                    <span style="font-size: 0.75rem; color: #888;">Today, 10:45 AM</span>
                </div>

                <!-- Step 2 -->
                <div style="position: relative; margin-bottom: 2rem;">
                    <div style="position: absolute; left: -2rem; top: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--primary-emerald); color: white; display: flex; align-items: center; justify-content: center; z-index: 2;">
                        <i class="ph ph-check" style="font-size: 0.8rem;"></i>
                    </div>
                    <h4 style="margin: 0; color: var(--primary-emerald);">Quality Check Passed</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin: 0;">Item verified by SnapResell AI.</p>
                    <span style="font-size: 0.75rem; color: #888;">Today, 11:30 AM</span>
                </div>

                <!-- Step 3 -->
                <div style="position: relative; margin-bottom: 2rem;">
                    <div style="position: absolute; left: -2rem; top: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--primary-indigo); color: white; display: flex; align-items: center; justify-content: center; z-index: 2; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);">
                        <i class="ph ph-truck" style="font-size: 0.8rem;"></i>
                    </div>
                    <h4 style="margin: 0; color: var(--primary-indigo);">Out for Delivery</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin: 0;">Courier is on the way to the meet-up spot / your address.</p>
                    <span style="font-size: 0.75rem; color: #888;">Estimated Arrival: 2:00 PM</span>
                </div>

                <!-- Step 4 -->
                <div style="position: relative;">
                    <div style="position: absolute; left: -2rem; top: 0; width: 24px; height: 24px; border-radius: 50%; background: var(--glass-border); display: flex; align-items: center; justify-content: center; z-index: 2;">
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: white;"></div>
                    </div>
                    <h4 style="margin: 0; color: var(--text-muted);">Delivered</h4>
                    <p class="text-muted" style="font-size: 0.875rem; margin: 0;">Pending confirmation.</p>
                </div>
            </div>
            
            <div class="mt-4" style="background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 8px; border: 1px dashed var(--primary-emerald);">
                <div class="flex gap-2 items-center">
                    <i class="ph ph-map-pin" style="color: var(--primary-emerald); font-size: 1.5rem;"></i>
                    <div>
                        <strong style="display: block;">Meet-up Point</strong>
                        <span class="text-muted">Central Park Cafe (Verified Safe Zone)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
