@extends('layouts.app')

@section('title', 'Attendance Report for ' . $student->name)

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
                        <h3 class="page-title">Attendance Report for {{ $student->name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Attendance Report</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table common-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Attendance Records</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('student.index') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped" id="attendanceTable">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Date</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date }}</td>
                                            <td>{{ $attendance->class->class_name }}</td>
                                            <td>
                                                @if($attendance->status == 'present')
                                                    <span class="badge bg-success">Present</span>
                                                @elseif($attendance->status == 'absent')
                                                    <span class="badge bg-danger">Absent</span>
                                                @else
                                                    <span class="badge bg-warning">Excused</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No attendance records found.</td>
                                        </tr>
                                    @endforelse
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
    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#attendanceTable')) {
                $('#attendanceTable').DataTable({
                    "order": [[0, "desc"]],
                    "responsive": true,
                    "ordering": true,
                    "pageLength": 10,
                    "searching": true,
                    "lengthChange": true
                });
            }
        });

    </script>
@endsection
