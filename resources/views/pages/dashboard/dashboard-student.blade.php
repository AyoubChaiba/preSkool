@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('main')
<div class="content container-fluid">

    <!-- Dashboard Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome, {{ $student->name }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>{{ $student->name }}</h6>
                            <p><strong>Class:</strong> {{ $student->class->class_name }}</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Fees</h6>
                            <p>${{ number_format($totalFees, 2) }}</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Total Paid Fees Widget -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Paid Fees</h6>
                            <p>${{ number_format($paidFees, 2) }}</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Fees Widget -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Pending Fees</h6>
                            <p>${{ number_format($pendingFees, 2) }}</p>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Grades -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Your Grades</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($student->grades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject->subject_name }}</td>
                                        <td>{{ $grade->exam->exam_name }}</td>
                                        <td>{{ $grade->grade }}</td>
                                        <td>{{ $grade->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No grade data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Attendance -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Your Attendance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Total Days</th>
                                    <th>Days Present</th>
                                    <th>Attendance Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDays = $student->attendance->count();
                                    $daysPresent = $student->attendance->where('status', 'present')->count();
                                    $attendanceRate = $totalDays > 0 ? ($daysPresent / $totalDays) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $totalDays }}</td>
                                    <td>{{ $daysPresent }}</td>
                                    <td>{{ number_format($attendanceRate, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
