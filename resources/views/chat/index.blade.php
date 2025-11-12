@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-lg border border-cyan-500/20 flex flex-col" style="height: 600px;">
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-cyan-400">Chat</h2>
        </div>
        
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($messages as $message)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-purple-500 flex items-center justify-center">
                            {{ substr($message->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-medium text-cyan-400">{{ $message->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-gray-300">{{ $message->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-700">
            <form id="chat-form" class="flex space-x-2">
                @csrf
                <input type="text" id="chat-input" name="message" placeholder="Nachricht schreiben..." required
                    class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 rounded transition">
                    Senden
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messagesContainer = document.getElementById('chat-messages');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        try {
            const response = await fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message })
            });

            if (response.ok) {
                input.value = '';
                loadMessages();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    function loadMessages() {
        fetch('{{ route("chat.messages") }}')
            .then(response => response.json())
            .then(messages => {
                messagesContainer.innerHTML = messages.map(msg => `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-500 to-purple-500 flex items-center justify-center">
                                ${msg.user.name.charAt(0)}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-medium text-cyan-400">${msg.user.name}</span>
                                <span class="text-xs text-gray-400">${new Date(msg.created_at).toLocaleTimeString('de-DE', {hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            <p class="text-gray-300">${msg.message}</p>
                        </div>
                    </div>
                `).join('');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
    }

    // Auto-refresh every 5 seconds
    setInterval(loadMessages, 5000);
});
</script>
@endpush
@endsection

