@extends('layouts.app')

@section('title', 'User Fees')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Fees</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li>
                            <li class="breadcrumb-item active">All Fees</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="page-title">Fees</h3>
                            @can('viewAny', Auth::user())
                                <a href="{{ route('fees.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                </a>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        @can('viewAny', Auth::user())
                                            <th class="text-end">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fees as $fee)
                                        <tr>
                                            <td>{{ $fee->id }}</td>
                                            <td>{{ $fee->student->name }}</td>
                                            <td>{{ number_format($fee->amount, 2) }}</td>
                                            <td>{{ $fee->payment_date }}</td>
                                            <td>
                                                <span class="badge
                                                    @if ($fee->status === 'paid') badge-success
                                                    @elseif ($fee->status === 'pending') badge-warning
                                                    @else badge-secondary @endif">
                                                    {{ ucfirst($fee->status) }}
                                                </span>
                                            </td>
                                            @can('viewAny', Auth::user())
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ route('fees.edit', $fee->id) }}" class="btn btn-sm bg-danger-light me-2">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('fees.destroy', $fee->id) }}" method="POST" style="display: inline;">
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
                                    text: 'Fee deleted successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Error deleting fee.',
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
