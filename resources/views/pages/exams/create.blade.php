@extends('layouts.app')

@section('title', 'Create Exam')

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
                        <h3 class="page-title">Create Exam</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Create Exam</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="examForm" method="POST" action="{{ route('exam.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Exam Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="exam_name" placeholder="Enter Exam Name">
                                        <div class="invalid-feedback exam_name_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Exam Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" id="date" name="exam_date" placeholder="DD-MM-YYYY">
                                        <div class="invalid-feedback exam_date_feedback"></div>
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
                                        <div class="invalid-feedback class_id_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="subject_id">
                                            <option value="">Select Subject</option>
                                        </select>
                                        <div class="invalid-feedback subject_id_feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="exam-submit">
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
                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('[name="' + key + '"]').addClass('is-invalid');
                    $('.' + key + '_feedback').text(value[0]);
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
                        $('#examForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Exam created successfully!',
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
                $(this).next('.invalid-feedback').text('');
            });
        });
    </script>
@endsection
