@extends('layouts.app')

@section('title', $ticket->subject)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-cyan-400">{{ $ticket->subject }}</h1>
            <div class="flex items-center space-x-2">
                @if($ticket->status === 'open')
                    <span class="px-3 py-1 text-sm rounded bg-green-500/20 text-green-400">Offen</span>
                @elseif($ticket->status === 'in_progress')
                    <span class="px-3 py-1 text-sm rounded bg-yellow-500/20 text-yellow-400">In Bearbeitung</span>
                @else
                    <span class="px-3 py-1 text-sm rounded bg-gray-500/20 text-gray-400">Geschlossen</span>
                @endif
            </div>
        </div>

        <div class="bg-gray-700/50 rounded p-4 mb-6">
            <p class="text-gray-300 whitespace-pre-wrap">{{ $ticket->message }}</p>
        </div>

        <div class="text-sm text-gray-400">
            Erstellt von {{ $ticket->user->name }} am {{ $ticket->created_at->format('d.m.Y H:i') }}
        </div>
    </div>

    <!-- Replies -->
    <div class="space-y-4">
        @foreach($replies as $reply)
            <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-medium text-cyan-400">{{ $reply->user->name }}</span>
                    <span class="text-sm text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</p>
            </div>
        @endforeach
    </div>

    <!-- Reply Form -->
    <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
        <h2 class="text-xl font-bold mb-4 text-cyan-400">Antworten</h2>
        <form method="POST" action="{{ route('support.reply', $ticket) }}">
            @csrf
            <textarea name="message" rows="4" required
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 mb-4"
                placeholder="Schreibe eine Antwort..."></textarea>
            <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                Antworten
            </button>
        </form>
    </div>
</div>
@endsection

