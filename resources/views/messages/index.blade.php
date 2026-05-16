@extends('layouts.app')

@section('content')
<div class="animate-fade-in chat-container glass" style="max-width: 800px; margin: 0 auto; height: 600px; padding: 0;">
    <div class="grid grid-cols-3" style="height: 100%;">
        <!-- Sidebar -->
        <div style="border-right: 1px solid var(--glass-border); overflow-y: auto;">
            <div style="padding: 1rem; border-bottom: 1px solid var(--glass-border);">
                <h3 style="margin: 0;">Messages</h3>
            </div>
            
            @forelse($chats as $chat)
            <a href="{{ route('messages', $chat->id) }}" class="flex items-center gap-2" style="padding: 1rem; {{ $selectedUser && $selectedUser->id == $chat->id ? 'background: rgba(99, 102, 241, 0.1);' : 'border-bottom: 1px solid var(--glass-border);' }} cursor: pointer; text-decoration: none; color: inherit;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $chat->role == 'seller' ? 'var(--primary-indigo)' : 'var(--primary-emerald)' }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ strtoupper(substr($chat->name, 0, 1)) }}
                </div>
                <div style="flex: 1; overflow: hidden;">
                    <h4 style="margin: 0; font-size: 1rem; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ $chat->name }}</h4>
                    <span class="text-muted" style="font-size: 0.8rem;">{{ ucfirst($chat->role) }}</span>
                </div>
            </a>
            @empty
            <div style="padding: 2rem; text-align: center;" class="text-muted">
                No conversations yet.
            </div>
            @endforelse
        </div>

        <!-- Chat Area -->
        <div style="grid-column: span 2; display: flex; flex-direction: column;">
            @if($selectedUser)
                <!-- Chat Header -->
                <div class="flex justify-between items-center" style="padding: 1rem; border-bottom: 1px solid var(--glass-border);">
                    <div class="flex items-center gap-2">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $selectedUser->role == 'seller' ? 'var(--primary-indigo)' : 'var(--primary-emerald)' }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 style="margin: 0;">{{ $selectedUser->name }} @if($selectedUser->role == 'seller') <i class="ph ph-seal-check" style="color: var(--primary-indigo);"></i> @endif</h4>
                            <span style="font-size: 0.8rem; color: var(--primary-emerald);">Online</span>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div id="chat-messages" style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($messages as $msg)
                        <div class="message {{ $msg->sender_id == Auth::id() ? 'sent' : 'received' }}">
                            <p style="margin: 0;">{{ $msg->message }}</p>
                            <span style="font-size: 0.7rem; color: {{ $msg->sender_id == Auth::id() ? 'rgba(255,255,255,0.7)' : '#888' }}; display: block; text-align: right; margin-top: 0.25rem;">{{ $msg->created_at->format('h:i A') }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Input Area -->
                <div style="padding: 1rem; border-top: 1px solid var(--glass-border);">
                    <form id="chat-form" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                        <input type="text" name="message" id="message-input" class="form-control" placeholder="Type a message..." style="flex: 1;" required>
                        <button type="submit" class="btn btn-primary" style="padding: 0 1.5rem;"><i class="ph ph-paper-plane-right"></i></button>
                    </form>
                </div>
            @else
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column;" class="text-muted">
                    <i class="ph ph-chat-circle-dots" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.2;"></i>
                    <p>Select a contact to start chatting</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;

    const chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const message = input.value;
            const receiverId = chatForm.querySelector('[name="receiver_id"]').value;

            if (!message.trim()) return;

            fetch('{{ route("messages.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                input.value = '';
                // Append message to UI
                const msgDiv = document.createElement('div');
                msgDiv.className = 'message sent';
                msgDiv.innerHTML = `
                    <p style="margin: 0;">${data.message}</p>
                    <span style="font-size: 0.7rem; color: rgba(255,255,255,0.7); display: block; text-align: right; margin-top: 0.25rem;">Just now</span>
                `;
                chatMessages.appendChild(msgDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        });
    }
});
</script>

<style>
    .message {
        max-width: 80%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        position: relative;
    }
    .sent {
        align-self: flex-end;
        background: var(--gradient-primary);
        color: white;
        border-bottom-right-radius: 2px;
    }
    .received {
        align-self: flex-start;
        background: white;
        color: #333;
        border-bottom-left-radius: 2px;
        border: 1px solid var(--glass-border, rgba(0, 0, 0, 0.1));
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
</style>
@endsection
