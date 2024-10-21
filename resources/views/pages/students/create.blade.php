@extends('layouts.app')

@section('title', 'Add Students')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap-datetimepicker.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}" />
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Add Students</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Add Student</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="studentForm" method="POST" action="{{ route('student.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Username <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="username" placeholder="Enter Username" >
                                        <div class="invalid-feedback username_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" placeholder="Enter Email" >
                                        <div class="invalid-feedback email_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Password <span class="login-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" placeholder="Enter Password">
                                        <div class="invalid-feedback password_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Phone Number <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone_number" placeholder="Enter Phone Number" >
                                        <div class="invalid-feedback phone_number_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Address </label>
                                        <input class="form-control" type="text" name="address" placeholder="Enter Address" >
                                        <div class="invalid-feedback address_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Gender <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <div class="invalid-feedback gender_error"></div>
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Date of Birth <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" name="date_of_birth" placeholder="DD-MM-YYYY" >
                                        <div class="invalid-feedback date_of_birth_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Admission Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" id="date" name="admission_date" placeholder="DD-MM-YYYY">
                                        <div class="invalid-feedback admission_date_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Parent <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="parent_id" >
                                            <option value="">Select Parent</option>
                                            @foreach($parents as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback parent_id_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class </label>
                                        <select class="form-control select" name="class_id" >
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback class_id_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Section </label>
                                        <select class="form-control select" name="section_id" >
                                            <option value="">Select Section</option>
                                        </select>
                                        <div class="invalid-feedback section_id_error"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
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
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/moment/moment.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap-datetimepicker.min.js") }}"></script>

    <script>
        $(document).ready(function() {
            function clearValidationErrors() {
                $('.error-text').text('');
                $('input, select').removeClass('is-invalid');
            }

            function showValidationErrors(errors) {
                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                });

            }

            $('#studentForm').on('submit', function(e) {
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
                        $('#studentForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Student created successfully!',
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
                        url: '/classes/' + classId + '/section',
                        type: 'GET',
                        success: function(sections) {
                            $('select[name="section_id"]').empty().append('<option value="">Select Section</option>');

                            $.each(sections, function(index, section) {
                                $('select[name="section_id"]').append('<option value="' + section.id + '">' + section.section_name + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching subjects:', xhr);
                        }
                    });
                } else {
                    $('select[name="section_id"]').empty().append('<option value="">Select Section</option>');
                }
            });

            $('input, select').on('input change', function() {
                $(this).next('.error-text').text('');
                $(this).removeClass('is-invalid');
            });
        });
        </script>

@endsection
