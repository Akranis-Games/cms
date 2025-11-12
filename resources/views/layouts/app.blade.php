<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel CMS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
    <!-- Holiday Animations Container -->
    <div id="holiday-animations"></div>

    <!-- Navigation -->
    <nav class="bg-gray-800 border-b border-cyan-500/20 sticky top-0 z-50 backdrop-blur-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                        LaravelCMS
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Home</a>
                        <a href="{{ route('news.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">News</a>
                        <a href="{{ route('forum.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Forum</a>
                        <a href="{{ route('shop.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Shop</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('chat.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Chat</a>
                        <a href="{{ route('support.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Support</a>
                        <a href="{{ route('ucp.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">UCP</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-purple-600 hover:bg-purple-700 transition">ACP</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-red-500/10 hover:text-red-400 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-cyan-500/10 hover:text-cyan-400 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-cyan-600 hover:bg-cyan-700 transition">Registrieren</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded relative m-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded relative m-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-cyan-500/20 mt-auto">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center text-gray-400">
                <p>&copy; {{ date('Y') }} LaravelCMS. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script src="{{ asset('js/holiday-animations.js') }}"></script>
</body>
</html>

