@extends('layouts.app')

@section('content')
<div class="animate-fade-in text-center" style="max-width: 600px; margin: 4rem auto;">
    <div class="glass" style="padding: 3rem;">
        <div style="width: 80px; height: 80px; background: var(--primary-emerald); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 2rem;">
            <i class="ph ph-check"></i>
        </div>
        <h2 style="color: var(--primary-emerald); margin-bottom: 1rem;">Payment Successful!</h2>
        <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 2rem;">Thank you for your purchase. Your receipt has been sent to your email.</p>

        <div class="glass" style="background: rgba(255,255,255,0.8); text-align: left; margin-bottom: 2rem;">
            <h4 style="border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 1rem;">Receipt #REC-{{ rand(10000, 99999) }}</h4>
            <div class="flex justify-between mb-1">
                <span class="text-muted">Date</span>
                <span>{{ date('M d, Y') }}</span>
            </div>
            <div class="flex justify-between mb-1">
                <span class="text-muted">Payment Method</span>
                <span>Card ending in ****4242</span>
            </div>
            <div class="flex justify-between mb-1">
                <span class="text-muted">Amount Paid</span>
                <span style="font-weight: bold; color: var(--primary-indigo);">Paid Successfully</span>
            </div>
        </div>

        <div class="flex gap-2 justify-center">
            <a href="/dashboard" class="btn btn-primary" style="font-size: 1.1rem;">Go to Dashboard to Track</a>
        </div>
    </div>
</div>
@endsection
