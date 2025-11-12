@extends('layouts.app')

@section('title', 'Neue News erstellen')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-lg p-8 border border-purple-500/20">
        <h2 class="text-3xl font-bold mb-6 bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">
            Neue News erstellen
        </h2>

        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">Titel</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium mb-2">Kategorie</label>
                <select id="category_id" name="category_id" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="excerpt" class="block text-sm font-medium mb-2">Kurzbeschreibung</label>
                <textarea id="excerpt" name="excerpt" rows="2"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">{{ old('excerpt') }}</textarea>
                @error('excerpt')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium mb-2">Inhalt</label>
                <textarea id="content" name="content" rows="10" required
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium mb-2">Bild</label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded">
                <label for="is_published" class="ml-2">Veröffentlichen</label>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 rounded transition">
                    Erstellen
                </button>
                <a href="{{ route('admin.news.index') }}" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded transition">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

