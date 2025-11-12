@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="text-center py-12">
        <h1 class="text-5xl font-bold mb-4 bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
            Willkommen beim Laravel CMS
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto">
            Ein modernes Content Management System mit allen Features die du brauchst
        </p>
    </div>

    <!-- Latest News -->
    <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
        <h2 class="text-2xl font-bold mb-6 text-cyan-400">Neueste News</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestNews as $news)
                <a href="{{ route('news.show', $news) }}" class="block bg-gray-700/50 rounded-lg p-4 hover:bg-gray-700 transition border border-cyan-500/10 hover:border-cyan-500/30">
                    @if($news->image)
                        <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover rounded mb-4">
                    @endif
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 text-xs rounded" style="background-color: {{ $news->category->color }}20; color: {{ $news->category->color }}">
                            {{ $news->category->name }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-cyan-400">{{ $news->title }}</h3>
                    <p class="text-sm text-gray-400 line-clamp-2">{{ $news->excerpt ?? Str::limit(strip_tags($news->content), 100) }}</p>
                    <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $news->user->name }}</span>
                        <span>{{ $news->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <p class="text-gray-400 col-span-full text-center">Noch keine News vorhanden.</p>
            @endforelse
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('news.index') }}" class="inline-block px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                Alle News anzeigen
            </a>
        </div>
    </div>
</div>
@endsection

