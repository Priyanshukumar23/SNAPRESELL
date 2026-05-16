@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 800px; margin: 2rem auto;">
    <h2 style="margin-bottom: 2rem; text-align: center;">Secure Checkout</h2>

    <div class="grid grid-cols-2" style="gap: 2rem;">
        <!-- Order Summary -->
        <div class="glass">
            <h3>Order Summary</h3>
            @foreach($cartItems as $item)
                <div class="flex items-center gap-2 mt-4" style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; margin-bottom: 1rem;">
                    @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="Product" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    @else
                        <div style="width: 60px; height: 60px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; border: 1px solid var(--glass-border);">
                            <i class="ph ph-image text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h4 style="margin: 0;">{{ $item->product->name }} <span class="text-muted" style="font-size: 0.875rem;">x{{ $item->quantity }}</span></h4>
                        <p class="text-muted" style="font-size: 0.875rem; margin: 0;">Sold by {{ $item->product->seller->name }}</p>
                        @if($item->product->seller->location)
                            <p class="text-muted" style="font-size: 0.75rem; margin: 0;"><i class="ph ph-map-pin"></i> {{ $item->product->seller->location }}</p>
                        @endif
                    </div>
                    <div style="margin-left: auto; font-weight: bold;">
                        Rs. {{ number_format($item->product->price * $item->quantity, 2) }}
                    </div>
                </div>
            @endforeach
            
            <div class="flex justify-between mt-2">
                <span class="text-muted">Subtotal</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-muted">Platform Fee</span>
                <span>Rs. {{ number_format($platformFee, 2) }}</span>
            </div>
            <div class="flex justify-between mt-2 font-bold" style="font-size: 1.25rem; border-top: 1px solid var(--glass-border); padding-top: 1rem; margin-top: 1rem;">
                <span>Total</span>
                <span id="display-total" style="color: var(--primary-indigo);">Rs. {{ number_format($total + $platformFee, 2) }}</span>
            </div>

            @if($superCoins > 0)
            <div class="mt-4" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); padding: 1rem; border-radius: 8px;">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-coin" style="color: #f59e0b; font-size: 1.5rem;"></i>
                        <div>
                            <h4 style="margin: 0; color: #f59e0b;">Super Coins</h4>
                            <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Available: {{ $superCoins }} (1 Coin = 1 Rs)</p>
                        </div>
                    </div>
                    <label class="switch" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="use-coins-toggle" onchange="toggleCoins()">
                        <span>Use Coins</span>
                    </label>
                </div>
                <div id="coins-input-container" style="display: none; margin-top: 1rem;">
                    <label>How many coins to use?</label>
                    <input type="number" id="coins-to-use" class="form-control" min="1" max="{{ min($superCoins, $total + $platformFee) }}" value="{{ min($superCoins, $total + $platformFee) }}" oninput="updateTotal()">
                </div>
            </div>
            @endif
        </div>

        <!-- Payment Form -->
        <div class="glass">
            <h3>Payment Method</h3>
            
            <!-- Tabs -->
            <div class="flex gap-2 mt-4 mb-4">
                <button class="btn btn-primary" style="flex: 1; padding: 0.5rem; border-radius: 8px;"><i class="ph ph-credit-card"></i> Card</button>
                <button class="btn btn-outline" style="flex: 1; padding: 0.5rem; border-radius: 8px;"><i class="ph ph-qr-code"></i> UPI / QR</button>
            </div>

            <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                @csrf
                <input type="hidden" name="use_coins" id="hidden-use-coins" value="0">
                <div class="form-group">
                    <label>Cardholder Name</label>
                    <input type="text" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Card Number</label>
                    <div style="position: relative;">
                        <input type="text" class="form-control" placeholder="0000 0000 0000 0000" required maxlength="19">
                        <i class="ph ph-credit-card" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;"></i>
                    </div>
                </div>
                <div class="flex gap-2 mb-4">
                    <div style="flex: 1;">
                        <label>Expiry Date</label>
                        <input type="text" class="form-control" placeholder="MM/YY" required maxlength="5">
                    </div>
                    <div style="flex: 1;">
                        <label>CVV</label>
                        <input type="password" class="form-control" placeholder="123" required maxlength="4">
                    </div>
                </div>

                <div class="text-center mb-4">
                    <span class="text-muted" style="font-size: 0.875rem;">Or pay via Random QR Code (Mockup)</span><br>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MockPayment12345" alt="QR Code" style="margin-top: 0.5rem; border-radius: 8px;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1rem;" id="submit-btn">
                    Pay Rs. {{ number_format($total + $platformFee, 2) }} Securely
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const grandTotal = {{ $total + $platformFee }};
    const maxCoins = {{ min($superCoins ?? 0, $total + $platformFee) }};

    function toggleCoins() {
        const isChecked = document.getElementById('use-coins-toggle').checked;
        const container = document.getElementById('coins-input-container');
        container.style.display = isChecked ? 'block' : 'none';
        updateTotal();
    }

    function updateTotal() {
        const isChecked = document.getElementById('use-coins-toggle').checked;
        let coinsUsed = 0;
        
        if (isChecked) {
            let inputVal = document.getElementById('coins-to-use').value;
            coinsUsed = parseFloat(inputVal);
            if (isNaN(coinsUsed) || coinsUsed < 0) coinsUsed = 0;
            if (coinsUsed > maxCoins) {
                coinsUsed = maxCoins;
                document.getElementById('coins-to-use').value = maxCoins;
            }
        }
        
        document.getElementById('hidden-use-coins').value = coinsUsed;
        
        let newTotal = grandTotal - coinsUsed;
        if (newTotal < 0) newTotal = 0;
        
        document.getElementById('display-total').innerText = 'Rs. ' + newTotal.toFixed(2);
        document.getElementById('submit-btn').innerText = 'Pay Rs. ' + newTotal.toFixed(2) + ' Securely';
    }
</script>
@endsection
