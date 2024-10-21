@extends('layouts.app')

@section('title', 'Create Grade Points')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Create Grade Points</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Create Grade Points</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="gradePointsForm" method="POST" action="{{ route('gradepoints.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Mark From <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="mark_from" placeholder="Enter Mark From" step="0.01">
                                        <div class="invalid-feedback mark_from_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Mark Upto <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="mark_upto" placeholder="Enter Mark Upto" step="0.01">
                                        <div class="invalid-feedback mark_upto_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Grade Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="grade_name" placeholder="Enter Grade Name">
                                        <div class="invalid-feedback grade_name_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Comment</label>
                                        <input class="form-control" type="text" name="comment" placeholder="Optional Comment">
                                        <div class="invalid-feedback comment_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="grade-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('[name="' + key + '"]').addClass('is-invalid');
                    $('.' + key + '_feedback').text(value[0]);
                });
            }

            $('#gradePointsForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#gradePointsForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Grade Points created successfully!',
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

            $('input').on('input', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').text('');
            });
        });
    </script>
@endsection
