@extends('layouts.app')

@section('title', 'Edit Exam')

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
                        <h3 class="page-title">Edit Exam</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Edit Exam</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="examForm" method="POST" action="{{ route('exam.update', $exam->id) }}">
                            @csrf
                            @method('PUT') <!-- Add this line to indicate the update method -->
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Exam Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="exam_name" placeholder="Enter Exam Name" value="{{ $exam->exam_name }}" required>
                                        <span class="text-danger error-text exam_name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Exam Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" name="exam_date" placeholder="DD-MM-YYYY" value="{{ \Carbon\Carbon::parse($exam->exam_date)->format('d-m-Y') }}" required>
                                        <span class="text-danger error-text exam_date_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ $class->id == $exam->class_id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text class_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject->id == $exam->subject_id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text subject_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="exam-submit">
                                        <button type="submit" class="btn btn-primary">Update</button> <!-- Change button text to "Update" -->
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
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#examForm').on('submit', function(e) {
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
                            text: 'Exam updated successfully!',
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
            });
        });
    </script>
@endsection
