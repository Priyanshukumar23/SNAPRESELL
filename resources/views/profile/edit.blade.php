@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 600px; margin: 2rem auto;">
    <!-- Profile Info Display -->
    <div class="glass" style="margin-bottom: 2rem; text-align: center; padding: 2rem;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-indigo), var(--primary-pink)); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; margin: 0 auto 1rem;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <h2 style="margin-bottom: 0.25rem;">{{ Auth::user()->name }}</h2>
        <p class="text-muted" style="margin-bottom: 0.5rem;">{{ Auth::user()->email }}</p>
        @if(Auth::user()->location)
            <p class="text-muted" style="margin-bottom: 1rem;"><i class="ph ph-map-pin"></i> {{ Auth::user()->location }}</p>
        @else
            <p class="text-muted" style="margin-bottom: 1rem;">Location not provided</p>
        @endif
        
        <div style="margin-bottom: 1.5rem;">
            <span class="badge" style="background: {{ Auth::user()->role === 'seller' ? 'rgba(99, 102, 241, 0.1)' : 'rgba(16, 185, 129, 0.1)' }}; color: {{ Auth::user()->role === 'seller' ? 'var(--primary-indigo)' : 'var(--primary-emerald)' }}; font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst(Auth::user()->role) }}
            </span>
        </div>

        <button type="button" class="btn btn-outline" onclick="toggleEditForm()">
            <i class="ph ph-pencil-simple"></i> Edit Profile
        </button>
    </div>

    <!-- Edit Profile Form (Hidden by default) -->
    <div id="edit-profile-form" class="glass" style="display: none;">
        <h3 style="margin-bottom: 1.5rem;">Edit Profile Details</h3>
        
        <form method="POST" action="{{ route('profile') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                @error('name')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                @error('email')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required placeholder="e.g. +1234567890">
                @error('phone')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', Auth::user()->location) }}" placeholder="e.g. New York, USA">
                @error('location')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 2rem 0;">
            <h4 style="margin-bottom: 1rem;">Change Password (Optional)</h4>

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                @error('password')
                    <span style="color: var(--primary-pink); font-size: 0.875rem; margin-top: 0.5rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirm New Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
function toggleEditForm() {
    const form = document.getElementById('edit-profile-form');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>
@endsection
