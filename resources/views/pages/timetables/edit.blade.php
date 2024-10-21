@extends('layouts.app')

@section('title', 'Edit Timetable')

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Timetable</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('timetables.index', $class->id) }}">Timetables</a></li>
                            <li class="breadcrumb-item active">Edit Timetable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <form id="editTimetableForm" method="POST" action="{{ route('timetables.update', $timetable->id) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="class_id" value="{{ $class->id }}">

                            <div class="row">

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Select Subject <span class="login-danger">*</span></label>
                                        <select class="form-control" name="subject_id" required>
                                            <option value="" disabled>Select Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject->id == $timetable->subject_id ? 'selected' : '' }}>
                                                    {{ $subject->subject_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text subject_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Select Teacher <span class="login-danger">*</span></label>
                                        <select class="form-control" name="teacher_id" required>
                                            <option value="" disabled>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ $teacher->id == $timetable->teacher_id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text teacher_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Select Section <span class="login-danger">*</span></label>
                                        <select class="form-control" name="section_id" required>
                                            <option value="" disabled>Select Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ $section->id == $timetable->section_id ? 'selected' : '' }}>
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text section_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Select Day of Week <span class="login-danger">*</span></label>
                                        <select class="form-control" name="day_of_week" required>
                                            <option value="" disabled>Select Day</option>
                                            <option value="Monday" {{ $timetable->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                                            <option value="Tuesday" {{ $timetable->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                            <option value="Wednesday" {{ $timetable->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                            <option value="Thursday" {{ $timetable->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                            <option value="Friday" {{ $timetable->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
                                            <option value="Saturday" {{ $timetable->day_of_week == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                        </select>
                                        <span class="text-danger error-text day_of_week_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Start Time <span class="login-danger">*</span></label>
                                        <input class="form-control" type="time" name="start_time" value="{{ \Carbon\Carbon::parse($timetable->start_time)->format('H:i') }}" required>
                                        <span class="text-danger error-text start_time_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>End Time <span class="login-danger">*</span></label>
                                        <input class="form-control" type="time" name="end_time" value="{{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}" required>
                                        <span class="text-danger error-text end_time_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update Timetable</button>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            $('#editTimetableForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showValidationErrors(xhr.responseJSON.errors);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.',
                                showConfirmButton: true,
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
