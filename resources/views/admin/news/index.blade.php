@extends('layouts.app')

@section('title', 'News verwalten')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">News verwalten</h1>
        <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded transition">Neue News</a>
    </div>

    <div class="bg-gray-800 rounded-lg border border-purple-500/20 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Titel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kategorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Autor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Aktionen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($news as $item)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">{{ $item->title }}</td>
                        <td class="px-6 py-4">{{ $item->category->name }}</td>
                        <td class="px-6 py-4">{{ $item->user->name }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_published)
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400">Veröffentlicht</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">Entwurf</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.news.edit', $item) }}" class="text-cyan-400 hover:text-cyan-300 text-sm">Bearbeiten</a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline" onsubmit="return confirm('Wirklich löschen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm">Löschen</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $news->links() }}
</div>
@endsection

