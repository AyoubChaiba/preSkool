@extends('layouts.app')

@section('title', 'User Students')

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')

    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Students</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">All Students</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Students</h3>
                                </div>
                                @can('viewAny', Auth::user())
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('student.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 table-striped" id="students">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Class Name</th>
                                        <th>Parent Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>gender</th>
                                        <th>Date of Birth</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>{{ $student->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $student->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $student->class->class_name }}</td>
                                            <td>{{ $student->parent->name }}</td>
                                            <td>{{ $student->user->email }}</td>
                                            <td>{{ $student->phone_number }}</td>
                                            <td>{{ $student->gender }}</td>
                                            <td>{{ $student->date_of_birth }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('student.grades', $student->id) }}" class="btn btn-sm bg-success-light me-2">
                                                        <i data-feather="award"></i>
                                                    </a>
                                                    <a href="{{ route('attendance.show', $student->id) }}" class="btn btn-sm bg-success-light me-2">
                                                        <i class="fas fa-clipboard"></i>
                                                    </a>
                                                    @can('viewAny', Auth::user())
                                                        <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('student.destroy', $student->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm bg-danger-light btn-delete me-2" aria-label="Delete">
                                                                <i class="feather-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
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

    <script>
    $(document).ready(function() {
        $('#students').DataTable();
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
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Student deleted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting student.',
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
