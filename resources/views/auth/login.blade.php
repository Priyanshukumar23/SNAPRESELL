@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 400px; margin: 4rem auto;">
    <div class="glass">
        <h2 class="text-center" style="margin-bottom: 2rem;">Welcome Back</h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="you@example.com">
                @error('email')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" style="display: inline; font-weight: normal; margin-left: 0.5rem;">Remember Me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Log In
            </button>

            <div class="text-center" style="margin-top: 1.5rem;">
                <p class="text-muted">Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
