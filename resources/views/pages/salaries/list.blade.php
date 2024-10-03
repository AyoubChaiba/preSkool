@extends('layouts.app')

@section('title', 'User Salaries')

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
                        <h3 class="page-title">Salaries</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('salary.index') }}"> Salaries</a></li>
                            <li class="breadcrumb-item active">All Salaries</li>
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
                                    <h3 class="page-title">Salaries</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('salary.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Teacher</th>
                                        <th>Salary Amount</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        @can('viewAny', Auth::user())
                                            <th class="text-end">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salaries as $salary)
                                        <tr>
                                            <td>{{ $salary->id }}</td>
                                            <td>{{ $salary->teacher->user->name }}</td>
                                            <td>{{ number_format($salary->amount, 2) }}</td>
                                            <td>{{ $salary->payment_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($salary->status === "paid")
                                                    <span class="badge badge-success">{{ ucfirst($salary->status) }}</span>
                                                @elseif ($salary->status === "pending")
                                                    <span class="badge badge-warning">{{ ucfirst($salary->status) }}</span>
                                                @elseif ($salary->status === "overdue")
                                                    <span class="badge badge-danger">{{ ucfirst($salary->status) }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($salary->status) }}</span>
                                                @endif
                                            </td>
                                            @can('viewAny', Auth::user())
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ route('salary.edit', $salary->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('salary.destroy', $salary->id) }}" method="POST" style="display: inline;">
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
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Salary deleted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting salary.',
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
