@extends('layouts.app')

@section('title', 'Forum')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Forum</h1>
        @auth
            <a href="#" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">Neuer Thread</a>
        @endauth
    </div>

    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="bg-gray-800 rounded-lg border border-cyan-500/20">
                <div class="p-4 border-b border-gray-700" style="border-left: 4px solid {{ $category->color }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-cyan-400">{{ $category->name }}</h2>
                            <p class="text-sm text-gray-400 mt-1">{{ $category->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400">{{ $category->threads->count() }} Threads</div>
                        </div>
                    </div>
                </div>
                @if($category->threads->count() > 0)
                    <div class="p-4 space-y-2">
                        @foreach($category->threads as $thread)
                            <a href="{{ route('forum.thread', $thread) }}" class="block p-3 bg-gray-700/50 rounded hover:bg-gray-700 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        @if($thread->is_pinned)
                                            <span class="text-yellow-400">📌</span>
                                        @endif
                                        <span class="font-medium text-cyan-400">{{ $thread->title }}</span>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{ $thread->posts->count() }} Antworten
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

