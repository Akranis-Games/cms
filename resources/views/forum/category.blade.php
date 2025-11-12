@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold" style="color: {{ $category->color }}">{{ $category->name }}</h1>
            <p class="text-gray-400 mt-2">{{ $category->description }}</p>
        </div>
        @auth
            <a href="#" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">Neuer Thread</a>
        @endauth
    </div>

    <div class="bg-gray-800 rounded-lg border border-cyan-500/20 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Thread</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Autor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Antworten</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Letzte Antwort</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($threads as $thread)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                @if($thread->is_pinned)
                                    <span class="text-yellow-400">📌</span>
                                @endif
                                <a href="{{ route('forum.thread', $thread) }}" class="text-cyan-400 hover:text-cyan-300 font-medium">
                                    {{ $thread->title }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $thread->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $thread->posts->count() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            @if($thread->latestPost)
                                {{ $thread->latestPost->created_at->diffForHumans() }}<br>
                                <span class="text-xs">von {{ $thread->latestPost->user->name }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">Keine Threads vorhanden.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $threads->links() }}
</div>
@endsection

