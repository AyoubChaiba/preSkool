@extends('layouts.app')

@section('title', 'Attendance Management')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .attendance-present {
            color: green;
            font-weight: bold;
        }

        .attendance-absent {
            color: red;
            font-weight: bold;
        }

        .attendance-excused {
            color: orange;
            font-weight: bold;
        }
    </style>
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Manage Attendance: {{ $class->class_name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Attendance</a></li>
                            <li class="breadcrumb-item active">Manage Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <form id="attendance-form">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Select Date <span class="login-danger">*</span></label>
                                        <input type="date" name="date" id="date" class="form-control">
                                        <div class="invalid-feedback">Please select a date.</div>
                                    </div>
                                </div>

                                <input type="hidden" id="class-id" name="class_id" value="{{ $class->id }}">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Section <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="section_id" id="section-id">
                                            <option value="">Select Section</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a section.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3 report-btn mx-auto">
                                    <button type="submit" class="btn btn-primary">Fetch Students</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                        <div id="student-list"></div>
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
            $('#attendance-form').on('submit', function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#date').removeClass('is-invalid');
                $('#section-id').removeClass('is-invalid');

                var date = $('#date').val();
                var section_id = $('#section-id').val();
                var class_id = $('#class-id').val();

                if (date && section_id) {
                    $.ajax({
                        url: '{{ route('attendance.fetchStudents') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            date: date,
                            section_id: section_id,
                            class_id: class_id
                        },
                        success: function(response) {
                            let studentTable = '';
                            $('#student-count-card').show();
                            $('#student-count-label').text("Total Students: " + response.students.length);
                            $('#student-count').text(response.students[0].section);
                            $('#student-date-label').text(date);

                            if (response.students.length > 0) {
                                studentTable = `
                                    <form id="save-attendance-form">
                                        <input type="hidden" name="date" value="${date}">
                                        <input type="hidden" name="section_id" value="${section_id}">
                                        <input type="hidden" name="class_id" value="${class_id}">
                                        <div class="table-responsive">
                                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Class</th>
                                                        <th>Name</th>
                                                        <th>Attendance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                `;

                                $.each(response.students, function(index, student) {
                                    var attendanceStatus = student.attendance_status || '';
                                    var statusClass = attendanceStatus === 'present' ? 'attendance-present' :
                                        attendanceStatus === 'absent' ? 'attendance-absent' :
                                        attendanceStatus === 'excused' ? 'attendance-excused' : '';

                                    studentTable += `
                                        <tr>
                                            <td>${student.id}</td>
                                            <td>${student.class}</td>
                                            <td>${student.name}</td>
                                            <td>
                                                ${
                                                    attendanceStatus ? `<span class="${statusClass}">${attendanceStatus.charAt(0).toUpperCase() + attendanceStatus.slice(1)}</span>` :
                                                    `<select class="form-control" name="status[${student.id}]">
                                                        <option value="present">Present</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="excused">Excused</option>
                                                    </select>`
                                                }
                                            </td>
                                        </tr>
                                    `;
                                });

                                studentTable += `</tbody></table>`;

                                if (response.students.some(student => student.attendance_status)) {
                                    studentTable += `
                                        <div class="col-12 col-sm-3 report-btn mx-auto my-4">
                                            <button type="button" class="btn btn mt-3" id="update-button">
                                                Update Attendance
                                            </button>
                                        </div>`;
                                } else {
                                    studentTable += `
                                        <div class="col-12 col-sm-3 report-btn mx-auto my-4">
                                            <button type="button" class="btn btn mt-3" id="save-button">
                                                Save Attendance
                                            </button>
                                        </div>`;
                                }

                                studentTable += `</form></div>`;
                            } else {
                                studentTable = '<div>No students found in this section.</div>';
                            }

                            $('#student-list').html(studentTable);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors.date) {
                                    $('#date').addClass('is-invalid').next('.invalid-feedback').text(errors.date[0]);
                                }
                                if (errors.section_id) {
                                    $('#section-id').addClass('is-invalid').next('.invalid-feedback').text(errors.section_id[0]);
                                }
                            } else {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        }
                    });
                } else {
                    if (!date) {
                        $('#date').addClass('is-invalid');
                    }
                    if (!section_id) {
                        $('#section-id').addClass('is-invalid');
                    }
                }
            });

            $(document).on('click', '#update-button', function() {
                $('#student-list').find('span').each(function() {
                    var currentStatus = $(this).text().trim();
                    var selectedValue = currentStatus.toLowerCase();

                    $(this).replaceWith(`
                        <select class="form-control" name="status[${$(this).closest('tr').find('td:first').text()}]">
                            <option value="present" ${selectedValue === 'present' ? 'selected' : ''}>Present</option>
                            <option value="absent" ${selectedValue === 'absent' ? 'selected' : ''}>Absent</option>
                            <option value="excused" ${selectedValue === 'excused' ? 'selected' : ''}>Excused</option>
                        </select>
                    `);
                });
                $(this).text('Save Attendance').attr('id', 'save-button');
            });

            $(document).on('click', '#save-button', function() {
                var form = $('#save-attendance-form');

                $.ajax({
                    url: '{{ route('attendance.save') }}',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Success!', response.message, 'success');
                        $('#student-list').empty();
                        $('#student-count-card').hide();
                        form[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message, 'error');
                    }
                });
            });
        });
    </script>
@endsection
