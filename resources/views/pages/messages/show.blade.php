@extends('layouts.app')

@section('title', 'Message Details')

@section('main')

<div class="content container-fluid">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Message Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('messages.index') }}">Messages</a></li>
                        <li class="breadcrumb-item active">Message {{ $message->id }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <h4 class="mb-0">Sender: <span class="text-primary">{{ $message->sender->name }}</span></h4>
                        </div>
                    </div>
                    <h4>Sent At: <span class="text-muted">{{ $message->created_at->format('Y-m-d H:i') }}</span></h4>

                    <hr>

                    <h4>Content:</h4>
                    <div class="bg-light p-3 rounded border">{{ $message->content }}</div>

                    <div class="text-end mt-3">
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">Back to Messages</a>
                        @can('delete', $message)
                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-delete" aria-label="Delete">
                                    <i class="fa fa-trash"></i> Delete Message
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.btn-delete').click(function(e) {
        e.preventDefault();

        const button = $(this);
        const form = button.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your message has been deleted.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "{{ route('messages.index') }}";
                        });
                    },
                    error: function(error) {
                        let errorMessage = 'Error deleting message.';
                        if (error.responseJSON && error.responseJSON.message) {
                            errorMessage = error.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
