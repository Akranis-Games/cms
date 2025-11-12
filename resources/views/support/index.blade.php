@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">Support</h1>
        <a href="{{ route('support.create') }}" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">Neues Ticket</a>
    </div>

    <div class="bg-gray-800 rounded-lg border border-cyan-500/20 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Betreff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Priorität</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Erstellt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Aktionen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('support.show', $ticket) }}" class="text-cyan-400 hover:text-cyan-300">
                                {{ $ticket->subject }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($ticket->status === 'open')
                                <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400">Offen</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">In Bearbeitung</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-gray-500/20 text-gray-400">Geschlossen</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($ticket->priority === 'high')
                                <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-400">Hoch</span>
                            @elseif($ticket->priority === 'medium')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400">Mittel</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-blue-500/20 text-blue-400">Niedrig</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $ticket->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('support.show', $ticket) }}" class="text-cyan-400 hover:text-cyan-300 text-sm">Ansehen</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Keine Tickets vorhanden.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

