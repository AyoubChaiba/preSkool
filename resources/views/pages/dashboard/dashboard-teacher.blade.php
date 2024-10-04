@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/simple-calendar/simple-calendar.css') }}">
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Welcome {{ Auth::user()->name }}!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Teacher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Students</h6>
                                <h3>{{ $studentsCount }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ asset('assets/img/icons/dash-icon-01.svg') }}" alt="Students Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Salary Earned</h6>
                                <h3>${{ number_format($salary, 2) }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ asset('assets/img/icons/dash-icon-04.svg') }}" alt="Salary Earned Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Pending Salary</h6>
                                <h3>${{ number_format($pendingSalary, 2) }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ asset('assets/img/icons/dash-icon-04.svg') }}" alt="Pending Salary Dashboard Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Courses</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th class="text-center">Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($assignedCourses->isEmpty())
                                        <tr>
                                            <td colspan="3" class="text-center">No assigned courses available.</td>
                                        </tr>
                                    @else
                                        @foreach($assignedCourses as $course)
                                            <tr>
                                                <td class="text-nowrap">{{ $course->id }}</td>
                                                <td class="text-nowrap">{{ $course->name }}</td>
                                                <td class="text-center">{{ $course->subject->name }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
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
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/calander.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
@endsection
