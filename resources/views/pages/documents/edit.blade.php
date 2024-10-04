@extends('layouts.app')

@section('title', 'Edit Document')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Document</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                            <li class="breadcrumb-item active">Edit Document</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="documentForm" method="POST" action="{{ route('documents.update', $document->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <label class="col-form-label">Current File: {{ basename($document->file_path) }}</label>
                                    <div class="form-group local-forms">
                                        <input class="form-control" type="file" name="file" accept=".pdf,.doc,.docx">
                                        <span class="text-danger error-text file_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="document-submit">
                                        <button type="submit" class="btn btn-primary">Update Document</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            function clearValidationErrors() {
                $('.error-text').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#documentForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#documentForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        showValidationErrors(errors);
                        Swal.close();
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            });

            $('input[type="file"]').on('change', function() {
                $(this).next('.error-text').text('');
            });
        });
    </script>
@endsection
