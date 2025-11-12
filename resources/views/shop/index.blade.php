@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Minecraft Shop</h1>

    @foreach($categories as $category)
        @if($category->products->where('is_active', true)->count() > 0)
            <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
                <h2 class="text-2xl font-bold mb-6 text-cyan-400">{{ $category->name }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category->products->where('is_active', true) as $product)
                        <div class="bg-gray-700/50 rounded-lg p-4 border border-cyan-500/10 hover:border-cyan-500/30 transition">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded mb-4">
                            @endif
                            <h3 class="text-lg font-semibold mb-2 text-cyan-400">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-purple-400">{{ number_format($product->price, 2) }} €</span>
                                @auth
                                    <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="inline">
                                        @csrf
                                        <input type="number" name="quantity" value="1" min="1" class="w-16 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-center mr-2">
                                        <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition text-sm">
                                            In Warenkorb
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded transition text-sm">
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection

