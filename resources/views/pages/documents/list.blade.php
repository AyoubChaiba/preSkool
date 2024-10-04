@extends('layouts.app')

@section('title', 'User Documents')

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
                        <h3 class="page-title">Documents</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                            <li class="breadcrumb-item active">All Documents</li>
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
                                    <h3 class="page-title">Documents</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('documents.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>File Name</th>
                                        <th>Uploaded By</th>
                                        <th>Upload Date</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $document)
                                        <tr>
                                            <td>{{ $document->id }}</td>
                                            <td>{{ basename($document->file_path) }}</td>
                                            <td>{{ $document->user->name }}</td>
                                            <td>{{ $document->created_at->format('d M Y') }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-sm bg-info-light me-2">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-warning-light me-2" aria-label="Delete">
                                                            <i class="feather-trash"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ asset($document->file_path) }}" class="btn btn-sm bg-info-light" download>
                                                        <i class="feather-download"></i>
                                                    </a>
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
                text: "This document will be permanently deleted!",
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
                                text: 'Document deleted successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting the document.',
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
