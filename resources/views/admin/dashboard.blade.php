@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">
        Admin Control Panel
    </h1>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20">
            <h3 class="text-lg font-semibold mb-2 text-purple-400">Benutzer</h3>
            <p class="text-3xl font-bold">{{ $stats['users'] }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20">
            <h3 class="text-lg font-semibold mb-2 text-purple-400">News</h3>
            <p class="text-3xl font-bold">{{ $stats['news'] }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20">
            <h3 class="text-lg font-semibold mb-2 text-purple-400">Offene Tickets</h3>
            <p class="text-3xl font-bold">{{ $stats['tickets'] }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20">
            <h3 class="text-lg font-semibold mb-2 text-purple-400">Ausstehende Bestellungen</h3>
            <p class="text-3xl font-bold">{{ $stats['orders'] }}</p>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/20">
        <h2 class="text-2xl font-bold mb-4 text-purple-400">Schnellzugriff</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('admin.news.index') }}" class="p-4 bg-gray-700/50 rounded hover:bg-gray-700 transition border border-purple-500/10">
                <h3 class="font-semibold text-purple-400 mb-2">News verwalten</h3>
                <p class="text-sm text-gray-400">News erstellen, bearbeiten und löschen</p>
            </a>
            <a href="#" class="p-4 bg-gray-700/50 rounded hover:bg-gray-700 transition border border-purple-500/10">
                <h3 class="font-semibold text-purple-400 mb-2">Benutzer verwalten</h3>
                <p class="text-sm text-gray-400">Benutzerkonten verwalten</p>
            </a>
        </div>
    </div>
</div>
@endsection

