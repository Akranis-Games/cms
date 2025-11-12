@extends('layouts.app')

@section('title', 'Meine Bestellungen')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Meine Bestellungen</h1>

    <div class="bg-gray-800 rounded-lg border border-cyan-500/20 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Bestellnummer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Produkte</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Gesamt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Datum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">#{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside">
                                @foreach($order->items as $item)
                                    <li>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 font-bold text-purple-400">{{ number_format($order->total, 2) }} €</td>
                        <td class="px-6 py-4">
                            @if($order->status === 'completed')
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400">Abgeschlossen</span>
                            @elseif($order->status === 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">Ausstehend</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-400">Fehlgeschlagen</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Keine Bestellungen vorhanden.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

