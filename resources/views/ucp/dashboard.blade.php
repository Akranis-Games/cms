@extends('layouts.app')

@section('title', 'UCP Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
        User Control Panel
    </h1>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
            <h3 class="text-lg font-semibold mb-2 text-cyan-400">Forum Posts</h3>
            <p class="text-3xl font-bold">{{ auth()->user()->forumPosts->count() }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
            <h3 class="text-lg font-semibold mb-2 text-cyan-400">Support Tickets</h3>
            <p class="text-3xl font-bold">{{ auth()->user()->supportTickets->count() }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
            <h3 class="text-lg font-semibold mb-2 text-cyan-400">Bestellungen</h3>
            <p class="text-3xl font-bold">{{ auth()->user()->shopOrders->count() }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
            <h3 class="text-lg font-semibold mb-2 text-cyan-400">Chat Nachrichten</h3>
            <p class="text-3xl font-bold">{{ auth()->user()->chatMessages->count() }}</p>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-cyan-500/20">
        <h2 class="text-2xl font-bold mb-4 text-cyan-400">Meine letzten Aktivitäten</h2>
        <div class="space-y-4">
            <p class="text-gray-400">Hier werden deine letzten Aktivitäten angezeigt...</p>
        </div>
    </div>
</div>
@endsection

