@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('main')
<div class="content container-fluid">

    <!-- Dashboard Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome to the Teacher Dashboard!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- General Statistics -->
    <div class="row">
        <!-- Total Students -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Students</h6>
                            <h3>{{ $studentsCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Classes</h6>
                            <h3>{{ $classesCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subjects -->
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-light w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total Subjects</h6>
                            <h3>{{ $subjectsCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-book"></i>
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
                            <h6>Total Salary</h6>
                            <h3>${{ number_format($totalSalary, 2) }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-dollar-sign"></i>
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
                            <h6>Pending Salary</h6>
                            <h3>${{ number_format($totalSalaryPending, 2) }}</h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Student List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Reports</h5>
                </div>
                <div class="card-body">
                    <p>Overview of statistics related to your class and subjects.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
