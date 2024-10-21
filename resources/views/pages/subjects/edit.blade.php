@extends('layouts.app')

@section('title', 'Edit Subject')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Subject</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('subject.index', $class->id) }}">Subjects</a></li>
                            <li class="breadcrumb-item active">Edit Subject</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="subjectForm" method="POST" action="{{ route('subject.update', $subject->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group local-forms">
                                        <label>Subject Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="subject_name" value="{{ $subject->subject_name }}" placeholder="Enter Subject Name">
                                        <span class="text-danger error-text subject_name_error invalid-feedback"></span>
                                    </div>
                                </div>
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update Subject</button>
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
                $('input').removeClass('is-invalid');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    let inputField = $('[name="' + key + '"]');
                    inputField.addClass('is-invalid');
                    inputField.next('.error-text').text(value[0]);
                });
            }

            $('#subjectForm').on('submit', function(e) {
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
                            title: 'Success',
                            text: 'Subject updated successfully!',
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

            $('input, select').on('input change', function() {
                $(this).next('.error-text').text('');
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endsection
