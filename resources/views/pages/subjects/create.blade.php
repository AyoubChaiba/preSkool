@extends('layouts.app')

@section('title', __('Add Subject'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ __('Add Subject') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('subject.index', $class->id) }}">{{ __('Subjects') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Add Subject') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="subjectForm" method="POST" action="{{ route('subject.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group local-forms">
                                        <label>{{ __('Subject Name') }} <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="subject_name" placeholder="{{ __('Enter Subject Name') }}" >
                                        <div class="invalid-feedback subject_name_error"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        (function($) {
            $(document).ready(function() {
                function clearValidationErrors() {
                    $('.error-text').text('');
                    $('input').removeClass('is-invalid');
                }

                function showValidationErrors(errors) {
                    $.each(errors, function(key, value) {
                        $('input[name="' + key + '"]').addClass('is-invalid');
                        $('.' + key + '_error').text(value[0]);
                    });
                }

                $('#subjectForm').on('submit', function(e) {
                    e.preventDefault();
                    clearValidationErrors();

                    Swal.fire({
                        title: '{{ __('Submitting...') }}',
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
                                title: '{{ __('Success') }}',
                                text: '{{ __('Subject added successfully!') }}',
                            }).then(() => {
                                window.location.href = response.redirect_url;
                            });
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            if (errors) {
                                showValidationErrors(errors);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('Error') }}',
                                    text: '{{ __('An unexpected error occurred. Please try again.') }}',
                                });
                            }
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
        })(jQuery);
    </script>
@endsection
