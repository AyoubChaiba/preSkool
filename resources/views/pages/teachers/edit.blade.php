@extends('layouts.app')

@section('title', 'Edit Teacher')

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
                        <h3 class="page-title">Edit Teacher</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.index') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Edit Teacher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="teacherForm" method="POST" action="{{ route('teacher.update', $teacher->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Username </label>
                                        <input class="form-control" type="text" name="username" value="{{ old('username', $teacher->name) }}" placeholder="Enter Username" >
                                        <span class="text-danger error-text username_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Email </label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', $teacher->user->email) }}" placeholder="Enter Email" >
                                        <span class="text-danger error-text email_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Password </label>
                                        <input class="form-control" type="password" name="password" placeholder="Leave blank to keep current password">
                                        <span class="text-danger error-text password_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Phone Number </label>
                                        <input class="form-control" type="text" name="phone_number" value="{{ old('phone_number', $teacher->phone_number) }}" placeholder="Enter Phone Number" >
                                        <span class="text-danger error-text phone_number_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Hire Date </label>
                                        <input class="form-control datetimepicker" type="text" name="hire_date" value="{{ old('hire_date', \Carbon\Carbon::parse($teacher->hire_date)->format('d-m-Y')) }}" placeholder="DD-MM-YYYY" >
                                        <span class="text-danger error-text hire_date_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class </label>
                                        <select class="form-control select" name="class_id" >
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ $teacher->class_id == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text class_id_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="subject_id" >
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $teacher->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text subject_id_error invalid-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="teacher-submit">
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
                    $('.' + key + '_error').text(value[0]);
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Teacher updated successfully!',
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
                $(this).removeClass('is-invalid');
                $(this).next('.error-text').text('');
            });
        });
    </script>
@endsection
