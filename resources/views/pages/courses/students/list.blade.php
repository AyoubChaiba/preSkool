@extends('layouts.app')

@section('title', 'Students in Course: ' . $course->name)

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Students in Course: {{ $course->name }}</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('course.index') }}">Courses</a></li>
                        <li class="breadcrumb-item active">Students</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                            <thead class="student-thread">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Enrollment Date</th>
                                    <th class="text-end">Attendance</th>
                                    <th class="text-end">Grades</th>
                                    <th class="text-end">Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->student->id }}</td>
                                        <td>{{ $enrollment->student->user->name }}</td>
                                        <td>{{ $enrollment->enrollment_date->format('d-m-Y') }}</td>
                                        <td class="text-end">
                                            @can('view', Auth::user())
                                                <div class="actions">
                                                    <a href="{{ route('create.attendance', ['student_id' => $enrollment->student->id, 'course_id' => $enrollment->course->id]) }}" class="btn btn-sm bg-danger-light me-2" title="Add Attendance">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="{{ route('show.attendance', ['student_id' => $enrollment->student->id, 'course_id' => $enrollment->course->id]) }}" class="btn btn-sm bg-danger-light" title="View Attendance">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                        </td>
                                        <td class="text-end">
                                            @can('view', Auth::user())
                                                <div class="actions">
                                                    <a href="{{ route('create.grade', ['student_id' => $enrollment->student->id, 'course_id' => $enrollment->course->id]) }}" class="btn btn-sm bg-danger-light me-2" title="Add Grades">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="{{ route('show.grade', ['student_id' => $enrollment->student->id, 'course_id' => $enrollment->course->id]) }}" class="btn btn-sm bg-danger-light" title="View Grades">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                        </td>
                                        <td class="">
                                            <button type="button" class="btn btn-sm bg-info-light btn-send-message" data-student-id="{{ $enrollment->student->user->id }}" title="Send Message">
                                                <i class="feather-send"></i> Send Message
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.btn-send-message').click(function(e) {
            e.preventDefault();

            const studentId = $(this).data('student-id');

            Swal.fire({
                title: 'Send Message',
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your message here...',
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const message = result.value;

                    $.ajax({
                        url: '{{ route('message.send') }}',
                        type: 'POST',
                        data: {
                            receiver_id: studentId,
                            content: message,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Sent!', 'Message sent successfully!', 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to send the message. Please try again.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
