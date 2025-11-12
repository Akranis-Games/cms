@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid md:grid-cols-2 gap-8">
        <div>
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg border border-cyan-500/20">
            @else
                <div class="w-full h-96 bg-gray-700 rounded-lg border border-cyan-500/20 flex items-center justify-center">
                    <span class="text-gray-400">Kein Bild</span>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div>
                <h1 class="text-4xl font-bold mb-4 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                    {{ $product->name }}
                </h1>
                <p class="text-2xl font-bold text-purple-400 mb-4">{{ number_format($product->price, 2) }} €</p>
                <p class="text-gray-300">{{ $product->description }}</p>
            </div>

            @if($product->is_active)
                @auth
                    <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="flex items-center space-x-4">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" class="w-20 px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-cyan-600 to-purple-600 hover:from-cyan-700 hover:to-purple-700 rounded font-medium transition">
                            In Warenkorb
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded font-medium transition text-center">
                        Login zum Kaufen
                    </a>
                @endauth
            @else
                <p class="text-red-400">Dieses Produkt ist derzeit nicht verfügbar.</p>
            @endif

            <div class="pt-6 border-t border-gray-700">
                <h3 class="font-semibold mb-2 text-cyan-400">Kategorie</h3>
                <p class="text-gray-300">{{ $product->category->name }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

