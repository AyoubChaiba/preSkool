@extends('layouts.app')

@section('title', 'User Students')

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}"> Students</a></li>
                            <li class="breadcrumb-item active">All Students</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by ID ..." id="search-id">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Name ..." id="search-name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Phone ..." id="search-phone">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="button" id="search-button" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Students</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>date admission</th>
                                        <th>parent</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="student-details.html" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="student-details.html">{{ $user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('user.show', $user->id) }}" class="btn btn-sm bg-success-light me-2">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger-light btn-delete" aria-label="Delete">
                                                            <i class="feather-trash"></i>
                                                        </button>
                                                    </form>
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
                                text: 'User deleted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting user.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });


        $('#search-button').click(function(e) {
            e.preventDefault();

            const searchData = {
                id: $('#search-id').val(),
                name: $('#search-name').val(),
                phone: $('#search-phone').val(),
            };
        });
    });
    </script>
@endsection
