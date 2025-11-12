@extends('layouts.app')

@section('title', 'News')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">News</h1>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            @forelse($news as $item)
                <article class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
                    <div class="flex items-center mb-3">
                        <span class="px-3 py-1 text-sm rounded" style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }}">
                            {{ $item->category->name }}
                        </span>
                        <span class="ml-auto text-sm text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <h2 class="text-2xl font-bold mb-3">
                        <a href="{{ route('news.show', $item) }}" class="text-cyan-400 hover:text-cyan-300">
                            {{ $item->title }}
                        </a>
                    </h2>
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" class="w-full h-64 object-cover rounded mb-4">
                    @endif
                    <p class="text-gray-300 mb-4">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 200) }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-400">Von {{ $item->user->name }}</span>
                        </div>
                        <a href="{{ route('news.show', $item) }}" class="text-cyan-400 hover:text-cyan-300 text-sm font-medium">
                            Weiterlesen →
                        </a>
                    </div>
                </article>
            @empty
                <p class="text-gray-400 text-center py-12">Noch keine News vorhanden.</p>
            @endforelse

            {{ $news->links() }}
        </div>

        <aside class="md:col-span-1">
            <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20 sticky top-20">
                <h3 class="text-xl font-bold mb-4 text-cyan-400">Kategorien</h3>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                        <li>
                            <a href="#" class="flex items-center justify-between p-2 rounded hover:bg-gray-700 transition">
                                <span style="color: {{ $category->color }}">{{ $category->name }}</span>
                                <span class="text-sm text-gray-400">{{ $category->news->count() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection

