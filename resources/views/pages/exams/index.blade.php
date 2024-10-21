@extends('layouts.app')

@section('title', 'List Exams')

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
                        <h3 class="page-title">Exams</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('exam.index', $class->id) }}">Exams</a></li>
                            <li class="breadcrumb-item active">All Exams</li>
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
                                    <h3 class="page-title">Exams</h3>
                                </div>
                                @can('viewAny', Auth::user())
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('exam.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Exam Name</th>
                                        <th>Class</th>
                                        <th>Date</th>
                                        @can('viewAny', Auth::user())
                                            <th class="text-end">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $exam)
                                        <tr>
                                            <td>{{ $exam->exam_name }}</td>
                                            <td>{{ $exam->class->class_name }}</td>
                                            <td>{{ $exam->exam_date }}</td>
                                            @can('viewAny', Auth::user())
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ route('exam.edit', $exam->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('exam.destroy', $exam->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm bg-danger-light btn-delete" aria-label="Delete">
                                                                <i class="feather-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endcan
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
                                text: 'Exam deleted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting exam.',
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