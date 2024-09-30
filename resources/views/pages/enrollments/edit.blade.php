@extends('layouts.app')

@section('title', 'Edit Enrollment')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}" />
@endsection

@section('main')

<div class="content container-fluid">

    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Edit Enrollment</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.index') }}">Enrollments</a></li>
                        <li class="breadcrumb-item active">Edit Enrollment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="enrollmentForm" action="{{ route('enrollment.update', $enrollment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group local-forms">
                                    <label>Student <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="student_id">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ $student->id == $enrollment->student_id ? 'selected' : '' }}>
                                                {{ $student->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text student_id_error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group local-forms">
                                    <label>Course <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="course_id">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ $course->id == $enrollment->course_id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text course_id_error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group local-forms calendar-icon">
                                    <label>Enrollment Date <span class="login-danger">*</span></label>
                                    <input class="form-control datetimepicker" type="text" name="enrollment_date" value="{{ old('enrollment_date', $enrollment->enrollment_date->format('d-m-Y')) }}" placeholder="DD-MM-YYYY">
                                    <span class="text-danger error-text enrollment_date_error"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="student-submit">
                                    <button type="submit" class="btn btn-primary">Update Enrollment</button>
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
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#enrollmentForm').on('submit', function(e) {
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
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#enrollmentForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated Successfully',
                            text: 'Enrollment has been updated!',
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

            $('input, select').on('input change', function() {
                $(this).next('.error-text').text('');
            });

            $('.datetimepicker').datetimepicker({
                format: 'DD-MM-YYYY'
            });
        });
    </script>

@endsection
