@extends('layouts.app')

@section('title', 'Manage Grades')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .loading-spinner {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Manage Grades for Class: {{ $class->class_name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Grades</a></li>
                            <li class="breadcrumb-item active">Manage Grades</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <form id="grades-form">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Exams <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="exam_id" id="exam-id">
                                            <option value="">Select Exams</option>
                                            @foreach ($exams as $exam)
                                                <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="exam-id-error">Please select an exam.</div>
                                    </div>
                                </div>

                                <input type="hidden" id="class-id" name="class_id" value="{{ $class->id }}">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="subject_id" id="subject-id">
                                            <option value="">Select Subject</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="subject-id-error">Please select a subject.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3 report-btn mx-auto">
                                    <button type="submit" class="btn btn-primary" id="fetch-btn">Fetch Students</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="row" id="student-count-card" style="display: none;">
            <div class="col-xl-3 col-sm-6 col-12 mx-auto">
                <div class="card inovices-card">
                    <div class="card-body">
                        <div class="inovices-widget-header">
                            <span class="inovices-widget-icon">
                                <img src="{{ asset('assets/img/icons/invoices-icon2.svg') }}" alt="">
                            </span>
                            <div class="inovices-dash-count">
                                <div class="inovices-amount" id="student-count"></div>
                            </div>
                        </div>
                        <p class="inovices-all" id="student-count-label"></p>
                        <p class="inovices-all" id="student-date-label"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div id="student-list" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select Section",
                allowClear: true,
                width: '100%'
            });

            $('#grades-form').on('submit', function(e) {
                e.preventDefault();

                const exam_id = $('#exam-id').val();
                const subject_id = $('#subject-id').val();
                const class_id = $('#class-id').val();

                if (!exam_id || !subject_id) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        text: 'Please select both an exam and subject before fetching students.',
                    });
                    return;
                }

                $('.loading-spinner').show();

                $('#fetch-btn').prop('disabled', true);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route('grades.fetchStudents') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        exam_id: exam_id,
                        subject_id: subject_id,
                        class_id: class_id
                    },
                    success: function(response) {
                        let studentTable = '';
                        $('#student-list').show();

                        if (response.students.length > 0) {
                            studentTable = `<form id="save-grades-form">
                                                <input type="hidden" name="exam_id" value="${response.exam.id}">
                                                <input type="hidden" name="subject_id" value="${response.subject.id}">
                                                <input type="hidden" name="class_id" value="${response.class.id}">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Name</th>
                                                                <th>Grade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>`;

                            $.each(response.students, function(index, student) {
                                var grade = response.grades[student.id] ? response.grades[student.id].grade : '';
                                var readOnly = grade ? 'readonly' : '';

                                studentTable += `
                                    <tr>
                                        <td>${student.id}</td>
                                        <td>${student.name}</td>
                                        <td>
                                            <input type="text" class="form-control" name="grades[${student.id}]" value="${grade}" ${readOnly}>
                                            <input type="hidden" name="student_id" value="${student.id}">
                                        </td>
                                    </tr>`;
                            });

                            studentTable += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-3 report-btn mx-auto my-4">
                                    <button type="submit" class="btn mt-3" id="save-btn" style="display: ${response.hasGrades ? 'none' : 'block'};">Save Grades</button>
                                    <button type="button" class="btn mt-3" id="enable-update-btn" style="display: ${response.hasGrades ? 'block' : 'none'};">Edit Grades</button>
                                </div>
                            </form>`;

                            $('#student-list').html(studentTable);

                            $('#enable-update-btn').on('click', function() {
                                $('input[name^="grades"]').prop('readonly', false);
                                $('#save-btn').show();
                                $('#save-btn').text("Update Grades");
                                $('#enable-update-btn').hide();
                            });
                        } else {
                            $('#student-list').html('<p>No students found for the selected exam and subject.</p>');
                        }

                        $('.loading-spinner').hide();
                        $('#fetch-btn').prop('disabled', false);
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error fetching students',
                            text: 'There was an error fetching the students list. Please try again.'
                        });

                        $('.loading-spinner').hide();
                        $('#fetch-btn').prop('disabled', false);
                    }
                });
            });

            $(document).on('submit', '#save-grades-form', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Saving...',
                    html: 'Please wait while the grades are being saved.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('grades.save') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Grades have been saved successfully!',
                        });

                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error saving the grades. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
@endsection
