@extends('layouts.app')

@section('title', 'Parent Dashboard')

@section('main')
<div class="content container-fluid">

    <!-- Dashboard Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome to the Parent Dashboard!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Children Overview -->
    <div class="row">
        @forelse ($children as $child)
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-light w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>{{ $child->name }}</h6>
                                <p><strong>Grade:</strong> {{ $child->class->class_name }}</p>
                                <p><strong>Total Fees Paid:</strong> ${{ number_format($child->fees->where('status', 'paid')->sum('amount'), 2) }}</p>

                            </div>
                            <div class="db-icon">
                                <i class="fas fa-child"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No children data available.</p>
            </div>
        @endforelse
    </div>

    <!-- Children Grades -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Children's Grades</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Child</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>exam</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($childrenGrades as $grade)
                                    <tr>
                                        <td>{{ $grade->student->name }}</td>
                                        <td>{{ $grade->subject->subject_name }}</td>
                                        <td>{{ $grade->grade }}</td>
                                        <td>{{ $grade->exam->exam_name }}</td>
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

    <!-- Children Attendance -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Children's Attendance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Child</th>
                                    <th>Total Days</th>
                                    <th>Days Present</th>
                                    <th>Attendance Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($children as $child)
                                    @php
                                        $totalDays = $child->attendance->count();
                                        $daysPresent = $child->attendance->where('status', 'present')->count();
                                        $attendanceRate = $totalDays > 0 ? ($daysPresent / $totalDays) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $child->name }}</td>
                                        <td>{{ $totalDays }}</td>
                                        <td>{{ $daysPresent }}</td>
                                        <td>{{ number_format($attendanceRate, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No attendance data available</td>
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
