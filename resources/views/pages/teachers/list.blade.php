@extends('layouts.app')

@section('title', 'Teachers Lists')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Teachers</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">All Teachers</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table displaying teachers -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Teachers</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('teacher.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Date Admission</th>
                                        <th>Subject</th>
                                        <th>Courses</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachers as $teacher)
                                        <tr>
                                            <td>{{ $teacher->id }}</td>
                                            <td>{{ $teacher->user->name }}</td>
                                            <td>{{ $teacher->user->email }}</td>
                                            <td>{{ $teacher->hire_date }}</td>
                                            <td>{{ $teacher->subject->name }}</td>
                                            <td>{{ $teacher->courses_count }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('teacher.edit', $teacher->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger-light btn-delete me-2" aria-label="Delete">
                                                            <i class="feather-trash"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm bg-info-light btn-send-message" data-teacher-id="{{ $teacher->user->id }}">
                                                        <i class="feather-send"></i> Send Message
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                            button.closest('tr').fadeOut();
                            Swal.fire('Deleted!', 'Teacher deleted successfully!', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Error!', 'Error deleting teacher.', 'error');
                        }
                    });
                }
            });
        });

        $('.btn-send-message').click(function(e) {
            e.preventDefault();
            const teacherId = $(this).data('teacher-id');

            Swal.fire({
                title: 'Send Message',
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your message here...',
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const message = result.value;

                    $.ajax({
                        url: '{{ route('message.send') }}',
                        type: 'POST',
                        data: {
                            receiver_id: teacherId,
                            content: message,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Sent!', 'Message sent successfully!', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Error!', 'Failed to send the message.', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
@endsection
