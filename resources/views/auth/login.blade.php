@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-gray-800 rounded-lg p-8 border border-cyan-500/20">
        <h2 class="text-3xl font-bold mb-6 text-center bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
            Login
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">E-Mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Passwort</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" id="remember" name="remember" class="rounded">
                <label for="remember" class="ml-2 text-sm">Angemeldet bleiben</label>
            </div>

            <button type="submit" class="w-full py-2 bg-gradient-to-r from-cyan-600 to-purple-600 hover:from-cyan-700 hover:to-purple-700 rounded font-medium transition">
                Einloggen
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-400">
            Noch kein Account? <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300">Registrieren</a>
        </p>
    </div>
</div>
@endsection

