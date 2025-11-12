@extends('layouts.app')

@section('title', $thread->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-cyan-400">{{ $thread->title }}</h1>
                <p class="text-sm text-gray-400 mt-2">
                    In <span style="color: {{ $thread->category->color }}">{{ $thread->category->name }}</span>
                    von {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}
                </p>
            </div>
            @if($thread->is_pinned)
                <span class="px-3 py-1 text-sm rounded bg-yellow-500/20 text-yellow-400">📌 Angepinnt</span>
            @endif
        </div>

        <div class="prose prose-invert max-w-none">
            {!! nl2br(e($thread->content)) !!}
        </div>
    </div>

    <!-- Posts -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-cyan-400">Antworten ({{ $posts->total() }})</h2>
        
        @foreach($posts as $post)
            <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-purple-500 flex items-center justify-center">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="font-medium text-cyan-400">{{ $post->user->name }}</span>
                            @if($post->user->isAdmin())
                                <span class="ml-2 px-2 py-0.5 text-xs rounded bg-purple-500/20 text-purple-400">Admin</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        @endforeach

        {{ $posts->links() }}
    </div>

    <!-- Reply Form -->
    @if(!$thread->is_locked || auth()->check() && auth()->user()->isModerator())
        <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
            <h2 class="text-xl font-bold mb-4 text-cyan-400">Antworten</h2>
            @auth
                <form method="POST" action="{{ route('forum.post.create', $thread) }}">
                    @csrf
                    <textarea name="content" rows="6" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 mb-4"
                        placeholder="Schreibe eine Antwort..."></textarea>
                    <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                        Antworten
                    </button>
                </form>
            @else
                <p class="text-gray-400">
                    <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300">Melde dich an</a> um zu antworten.
                </p>
            @endauth
        </div>
    @else
        <div class="bg-gray-800 rounded-lg p-6 border border-red-500/20">
            <p class="text-red-400">Dieser Thread ist gesperrt.</p>
        </div>
    @endif
</div>
@endsection

