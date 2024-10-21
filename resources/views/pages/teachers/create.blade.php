@extends('layouts.app')

@section('title', 'Add Teacher')

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
                        <h3 class="page-title">Add Teacher</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Add Teacher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="teacherForm" method="POST" action="{{ route('teacher.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="username" placeholder="Enter Name">
                                        <span class="text-danger error-text username_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" placeholder="Enter Email">
                                        <span class="text-danger error-text email_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Password <span class="login-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" placeholder="Enter Password">
                                        <span class="text-danger error-text password_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Phone Number <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone_number" placeholder="Enter Phone Number">
                                        <span class="text-danger error-text phone_number_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Hire Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" name="hire_date" id="date" placeholder="DD-MM-YYYY">
                                        <span class="text-danger error-text hire_date_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="class_id">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text class_id_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="subject_id">
                                            <option value="">Select Subject</option>
                                        </select>
                                        <span class="text-danger error-text subject_id_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="teacher-submit">
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            function clearValidationErrors() {
                $('.error-text').text('');
                $('input, select').removeClass('is-invalid');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    let inputField = $('[name="' + key + '"]');
                    inputField.addClass('is-invalid');
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#teacherForm').on('submit', function(e) {
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
                        $('#teacherForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Teacher created successfully!',
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

            $('select[name="class_id"]').change(function() {
                let classId = $(this).val();

                if (classId) {
                    $.ajax({
                        url: '/classes/' + classId + '/subjects',
                        type: 'GET',
                        success: function(subjects) {
                            $('select[name="subject_id"]').empty().append('<option value="">Select Subject</option>');

                            $.each(subjects, function(index, subject) {
                                $('select[name="subject_id"]').append('<option value="' + subject.id + '">' + subject.subject_name + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching subjects:', xhr);
                        }
                    });
                } else {
                    $('select[name="subject_id"]').empty().append('<option value="">Select Subject</option>');
                }
            });

            $('input, select').on('input change', function() {
                $(this).next('.error-text').text('');
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endsection
