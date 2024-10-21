@extends('layouts.app')

@section('title', 'Edit Grade Points')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Grade Points</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Edit Grade Points</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="gradePointsForm" method="POST" action="{{ route('gradepoints.update', $gradepoint->id) }}">
                            @csrf
                            @method('PUT') <!-- Add this to indicate PUT request -->
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Mark From <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="mark_from" value="{{ $gradepoint->mark_from }}" required>
                                        <span class="text-danger error-text mark_from_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Mark Upto <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="mark_upto" value="{{ $gradepoint->mark_upto }}" required>
                                        <span class="text-danger error-text mark_upto_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Grade Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="grade_name" value="{{ $gradepoint->grade_name }}" required>
                                        <span class="text-danger error-text grade_name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Comment</label>
                                        <input class="form-control" type="text" name="comment" value="{{ $gradepoint->comment }}">
                                        <span class="text-danger error-text comment_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="grade-submit">
                                        <button type="submit" class="btn btn-primary">Update</button>
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

            $('#gradePointsForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Updating...',
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: 'Grade Points updated successfully!',
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.error;
                        showValidationErrors(errors);
                        Swal.close();
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            });

            $('input').on('input', function() {
                $(this).next('.error-text').text('');
            });
        });
    </script>
@endsection
