@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 500px; margin: 4rem auto;">
    <div class="glass">
        <h2 class="text-center" style="margin-bottom: 2rem;">Create an Account</h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">
                @error('name')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
                @error('email')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="e.g. +1234567890">
                @error('phone')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" placeholder="e.g. New York, USA">
                @error('location')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Join as a...</label>
                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <label class="role-option" style="flex: 1; cursor: pointer;">
                        <input type="radio" name="role" value="buyer" checked style="display: none;">
                        <div class="role-card" onclick="this.previousElementSibling.click()">
                            <div class="role-icon">🛍️</div>
                            <div class="role-name">Buyer</div>
                        </div>
                    </label>
                    <label class="role-option" style="flex: 1; cursor: pointer;">
                        <input type="radio" name="role" value="seller" style="display: none;">
                        <div class="role-card" onclick="this.previousElementSibling.click()">
                            <div class="role-icon">💰</div>
                            <div class="role-name">Seller</div>
                        </div>
                    </label>
                </div>
                @error('role')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <style>
                .role-card {
                    background: rgba(255, 255, 255, 0.05);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    text-align: center;
                    transition: all 0.3s ease;
                }
                .role-option input:checked + .role-card {
                    background: var(--gradient-primary);
                    border-color: transparent;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }
                .role-icon {
                    font-size: 1.5rem;
                    margin-bottom: 0.5rem;
                }
                .role-name {
                    font-weight: 600;
                    font-size: 0.9rem;
                }
            </style>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                @error('password')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Sign Up
            </button>

            <div class="text-center" style="margin-top: 1.5rem;">
                <p class="text-muted">Already have an account? <a href="{{ route('login') }}">Log In</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
