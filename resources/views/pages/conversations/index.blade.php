@extends('layouts.app')

@section('title', 'Conversations')

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />
    <style>
        .conversation-list-container {
            height: calc(100vh - 300px);
            overflow-y: auto;
        }
        .page-header {
            padding: 20px 0;
        }
        .breadcrumb {
            background: none;
            margin-bottom: 0;
        }
        .btn-custom {
            margin-bottom: 20px;
        }
        .conversation-item {
            cursor: pointer;
        }

        .badge {
            padding: 5px;
            font-size: 12px;
            color: white;
        }

        .badge.bg-danger {
            background-color: red;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-block;
            text-align: center;

        }
        .select2-container {
            z-index: 1050 !important;
        }

        .select2-container .select2-dropdown {
            z-index: 999999 !important;
        }


    </style>
@endsection

@section('main')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Conversations</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">Conversations</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12">

            <div class="card shadow-sm ">
                <div class="card-body conversation-list-container">
                    <div class="btn-custom">
                        <button id="addUserButton" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <ul class="list-group" id="conversationList">
                        <li class="active list-group-item">
                            <i class="fas fa-comments"></i> Conversations
                        </li>
                        @foreach ($conversations as $conversation)
                            <li class="list-group-item conversation-item" data-id="{{ $conversation->id }}">
                                <strong>{{ $conversation->sender->username }}</strong> with <strong>{{ $conversation->receiver->username }}</strong>
                                @if ($conversation->unread_count > 0)
                                    <span class="badge bg-danger rounded-circle" style="float: right;">
                                        {{ $conversation->unread_count }}
                                    </span>
                            @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm conversation-list-container">
                <div class="card-body h-100" id="messages-container">
                    <div class="d-flex flex-column align-items-center justify-content-center my-4 h-100">
                        <i class="fas fa-envelope mb-2" style="font-size: 24px;"></i>
                        <h4 class="email-header">Select a conversation to view messages</h4>
                    </div>
                    <div id="messageList"></div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="modal fade" id="userSelectModal" tabindex="-1" aria-labelledby="userSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSelectModalLabel">Select User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="userList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="startConversationButton" class="btn btn-primary">Start Conversation</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js-content')
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function showError(message) {
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

            function fetchUsers() {

                $.ajax({
                    url: '{{ route('users.index') }}',
                    method: 'GET',
                    success: function(users) {
                        $('#userList').empty().append('<select class="form-select select" id="userSelect"><option value="" disabled selected>Select a user</option></select>');
                        users.forEach(user => {
                            $('#userSelect').append(
                                `<option value="${user.id}">${user.username} <span class="text-muted">(${user.role})</span></option>`
                            );
                        });
                        $('.select').select2({
                            placeholder: "Select Section",
                            width: '100%',
                            dropdownParent: $('#userSelectModal'),
                            zIndex: 999999
                        });
                    },
                    error: function() {
                        showError('Unable to fetch users.');
                    }
                });
            }

            $('#addUserButton').on('click', function() {
                fetchUsers();
                $('#userSelectModal').modal('show');
            });


            $(document).on('click', '.conversation-item', function() {
                const conversationId = $(this).data('id');

                $.ajax({
                    url: `/conversations/${conversationId}/messages`,
                    method: 'GET',
                    success: function(response) {
                        $('#messages-container').html(response);
                        $('#messageList').empty();

                        response.messages.forEach(message => {
                            $('#messageList').append(`
                                <div class="message-item">
                                    <strong>${message.sender_username}:</strong> ${message.content}
                                    <small class="text-muted">${message.created_at}</small>
                                </div>
                            `);
                        });

                        $.post(`/conversations/${conversationId}/markAsRead`, function() {
                            $(`[data-id=${conversationId}] .badge`).remove();
                        });
                    },
                    error: function() {
                        alert('Unable to fetch messages.');
                    }
                });
            });

            $('#startConversationButton').on('click', function() {
                const selectedUserId = $('#userSelect').val();

                if (selectedUserId) {
                    $.post('/conversations/create', { user_id: selectedUserId })
                    .done(function(response) {
                        const conversationExists = $('#conversationList').find(`li[data-id="${response.conversation.id}"]`).length > 0;

                        if (!conversationExists) {
                            const newConversationItem = `<li class="list-group-item conversation-item" data-id="${response.conversation.id}">
                                <strong>${response.conversation.sender_name}</strong> with <strong>${response.conversation.receiver_name}</strong>
                            </li>`;

                            $('#conversationList').append(newConversationItem);
                        }

                        $('#userSelectModal').modal('hide');

                        $(`#conversationList li[data-id="${response.conversation.id}"]`).click();
                    })
                    .fail(function(xhr) {
                        const errorResponse = xhr.responseJSON;
                        showError(errorResponse.message || 'Unable to create conversation.');
                    });
                } else {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Please select a user.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });



        });
    </script>
@endsection


