
<style>
    .message-wrapper {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }
    .message-header {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .message {
        padding: 10px;
        border-radius: 15px;
        max-width: 70%;
    }
    .message.sent {
        background-color: #e1ffc7;
        margin-left: auto;
        text-align: right;
    }
    .message.received {
        background-color: #f1f0f0;
        margin-left: auto;
        text-align: right;
    }
    .message-content {
        margin: 0;
    }
    .message-time {
        display: block;
        font-size: 0.8em;
        color: #777;
        margin-top: 5px;
    }
    .messages-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
</style>


@if ($messages->isEmpty())
    <div class="messages-list" style="height: calc(100vh - 406px);padding: 10px; border: 1px solid #ddd; border-radius: 10px;">
        <div class="d-flex align-items-center justify-content-center w-100 h-100">
            <h4 class="text-center text-muted">No messages in this conversation yet.</h4>
        </div>
    </div>
@else
    <div class="messages-list" style=" height: calc(100vh - 406px);overflow-y: auto;padding: 10px; border: 1px solid #ddd; border-radius: 10px;">
        @foreach ($messages as $message)
            <div class="message-wrapper {{ $message->user_id == auth()->id() ? 'sent' : 'received' }}" >
                <div class="message-header">
                    <strong>{{ $message->user_id == auth()->id() ? 'You' : $message->user->username }}</strong>
                </div>
                <div class="message {{ $message->user_id == auth()->id() ? 'sent' : 'received' }}">
                    <p class="message-content">{{ $message->message }}</p>
                    <small class="message-time">{{ $message->created_at->format('Y-m-d H:i') }}</small>
                </div>
            </div>
        @endforeach
    </div>
@endif

<form id="messageForm" action="{{ route('messages.store', $conversation->id) }}" class="mt-3">
    @csrf
    <div class="input-group">
        <input type="text" name="message" id="messageInput" class="form-control" placeholder="Type your message..." required autocomplete="off">
        <button type="submit" class="btn btn-primary">Send</button>
    </div>
</form>

    <script>
        $(document).ready(function() {
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('.messages-list').append(`
                            <div class="message-wrapper sent">
                                <div class="message-header">
                                    <strong>You</strong>
                                </div>
                                <div class="message sent">
                                    <p class="message-content">${response.message}</p>
                                    <small class="message-time">${response.created_at}</small>
                                </div>
                            </div>
                        `);

                        $('.messages-list').scrollTop($('.messages-list')[0].scrollHeight);

                        $('#messageInput').val('');
                    },
                    error: function(xhr) {
                        alert('Error sending message: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>

