@extends('layouts.app')

@section('title', 'Neues Support Ticket')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gray-800 rounded-lg p-8 border border-cyan-500/20">
        <h2 class="text-3xl font-bold mb-6 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
            Neues Support Ticket
        </h2>

        <form method="POST" action="{{ route('support.store') }}">
            @csrf

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium mb-2">Betreff</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                @error('subject')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium mb-2">Priorität</label>
                <select id="priority" name="priority" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="low">Niedrig</option>
                    <option value="medium" selected>Mittel</option>
                    <option value="high">Hoch</option>
                </select>
                @error('priority')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="message" class="block text-sm font-medium mb-2">Nachricht</label>
                <textarea id="message" name="message" rows="8" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                    Ticket erstellen
                </button>
                <a href="{{ route('support.index') }}" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded transition">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

