@extends('layouts.app')

@section('title', $news->title)

@section('content')
<article class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-lg p-8 border border-cyan-500/20">
        <div class="flex items-center mb-4">
            <span class="px-3 py-1 text-sm rounded" style="background-color: {{ $news->category->color }}20; color: {{ $news->category->color }}">
                {{ $news->category->name }}
            </span>
            <span class="ml-auto text-sm text-gray-400">{{ $news->created_at->format('d.m.Y H:i') }}</span>
        </div>

        <h1 class="text-4xl font-bold mb-4 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
            {{ $news->title }}
        </h1>

        @if($news->image)
            <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-full h-96 object-cover rounded mb-6">
        @endif

        <div class="prose prose-invert max-w-none mb-8">
            {!! $news->content !!}
        </div>

        <div class="border-t border-gray-700 pt-6 mb-8">
            <div class="flex items-center justify-between text-sm text-gray-400">
                <span>Von {{ $news->user->name }}</span>
                <span>{{ $news->views }} Aufrufe</span>
            </div>
        </div>

        <!-- Comments -->
        <div class="border-t border-gray-700 pt-8">
            <h2 class="text-2xl font-bold mb-6 text-cyan-400">Kommentare ({{ $comments->count() }})</h2>

            @auth
                <form method="POST" action="{{ route('news.comment', $news) }}" class="mb-8">
                    @csrf
                    <textarea name="content" rows="4" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 mb-4"
                        placeholder="Schreibe einen Kommentar..."></textarea>
                    <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                        Kommentar absenden
                    </button>
                </form>
            @else
                <p class="text-gray-400 mb-8">
                    <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300">Melde dich an</a> um zu kommentieren.
                </p>
            @endauth

            <div class="space-y-6">
                @forelse($comments as $comment)
                    <div class="bg-gray-700/50 rounded-lg p-4 border border-cyan-500/10">
                        <div class="flex items-center mb-2">
                            <span class="font-medium text-cyan-400">{{ $comment->user->name }}</span>
                            <span class="ml-auto text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-300">{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">Noch keine Kommentare.</p>
                @endforelse
            </div>
        </div>
    </div>
</article>
@endsection

