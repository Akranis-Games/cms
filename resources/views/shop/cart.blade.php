@extends('layouts.app')

@section('title', 'Warenkorb')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Warenkorb</h1>

    @if(count($cart) > 0)
        <div class="bg-gray-800 rounded-lg border border-cyan-500/20 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Produkt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Menge</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Preis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Gesamt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($cart as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item['name'] }}</td>
                            <td class="px-6 py-4">{{ $item['quantity'] }}</td>
                            <td class="px-6 py-4">{{ number_format($item['price'], 2) }} €</td>
                            <td class="px-6 py-4">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-700">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold">Gesamt:</td>
                        <td class="px-6 py-4 font-bold text-purple-400">{{ number_format($total, 2) }} €</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <form method="POST" action="{{ route('shop.checkout') }}">
                @csrf
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-600 to-purple-600 hover:from-cyan-700 hover:to-purple-700 rounded font-medium transition">
                    Zur Kasse
                </button>
            </form>
        </div>
    @else
        <div class="bg-gray-800 rounded-lg p-12 border border-cyan-500/20 text-center">
            <p class="text-gray-400 text-lg mb-4">Dein Warenkorb ist leer.</p>
            <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                Zum Shop
            </a>
        </div>
    @endif
</div>
@endsection

