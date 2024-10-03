@extends('layouts.app')

@section('title', 'Edit Attendance for ' . $attendance->student->user->name)

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
                        <h3 class="page-title">Edit Attendance for : {{ $attendance->student->user->name }} | {{ $attendance->course->name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('show.attendance', ['student_id' => $attendance->student->id , 'course_id' => $attendance->course->id ]) }}">Attendance</a></li>
                            <li class="breadcrumb-item active">Edit Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="attendanceForm" method="POST" action="{{ route('update.attendance', $attendance->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Attendance Date <span class="login-danger">*</span></label>
                                        <input class="form-control" type="date" name="attendance_date" value="{{ $attendance->attendance_date }}">
                                        <span class="text-danger error-text attendance_date_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Status <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="status">
                                            <option value="">Select Status</option>
                                            <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                                            <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                            <option value="excused" {{ $attendance->status == 'excused' ? 'selected' : '' }}>Excused</option>
                                        </select>
                                        <span class="text-danger error-text status_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
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
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>

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

            $('#attendanceForm').on('submit', function(e) {
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
                            text: 'Attendance updated successfully!',
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
        });
    </script>
@endsection
