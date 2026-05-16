<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapResell | Modern Second-hand Marketplace</title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="SnapResell is a premium, modern marketplace to buy and sell second-hand items with AI price recommendations and EcoPoints.">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <nav class="navbar glass animate-fade-in" style="margin: 1rem 2rem; border-radius: 20px;">
        <div class="nav-brand">
            <a href="/" style="background: linear-gradient(135deg, var(--primary-indigo), var(--primary-pink)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SnapResell</a>
        </div>
        <div class="nav-links">
            <a href="/products">Explore</a>
            @auth
                <a href="/dashboard">Dashboard</a>
                <a href="/messages"><i class="ph ph-chat-circle-dots"></i> Messages</a>
                <a href="/profile"><i class="ph ph-user"></i> Profile</a>
                <form action="/logout" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="padding: 0.5rem 1rem;">Logout</button>
                </form>
            @else
                <a href="/login" class="btn btn-outline" style="padding: 0.5rem 1rem;">Log In</a>
                <a href="/register" class="btn btn-primary" style="padding: 0.5rem 1rem;">Sign Up</a>
            @endauth
        </div>
    </nav>

    <div class="container" style="min-height: calc(100vh - 150px);">
        @if(session('success'))
            <div class="glass animate-fade-in" style="background: rgba(16, 185, 129, 0.2); border-color: var(--primary-emerald); color: var(--primary-emerald); padding: 1rem; margin-bottom: 1.5rem; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="glass animate-fade-in" style="background: rgba(236, 72, 153, 0.2); border-color: var(--primary-pink); color: var(--primary-pink); padding: 1rem; margin-bottom: 1.5rem; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="glass" style="margin-top: 4rem; text-align: center; border-radius: 20px 20px 0 0; border-bottom: none;">
        <p>&copy; {{ date('Y') }} SnapResell. Sustainable & Smart Reselling.</p>
    </footer>
</body>
</html>
