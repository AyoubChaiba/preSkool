@extends('layouts.app')

@section('title', 'List Subjects')

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
                        <h3 class="page-title">Subjects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('subject.index') }}"> Subjects</a></li>
                            <li class="breadcrumb-item active">All Subjects</li>
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
                                    <h3 class="page-title">Subjects</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('subject.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Courses</th>
                                        <th>Teachers</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->id }}</td>
                                            <td>
                                                {{ $subject->name }}
                                            </td>
                                            <td>{{ $subject->courses_count  }}</td>
                                            <td>{{ $subject->teachers_count  }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    {{-- <a href="{{ route('subject.show', $subject->id) }}" class="btn btn-sm bg-success-light me-2">
                                                        <i class="feather-eye"></i>
                                                    </a> --}}
                                                    <a href="{{ route('subject.edit', $subject->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('subject.destroy', $subject->id) }}" method="POST" style="display: inline;">
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
                                text: 'Subject deleted successfully!',
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
