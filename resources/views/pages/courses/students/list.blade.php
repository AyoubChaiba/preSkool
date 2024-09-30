@extends('layouts.app')

@section('title', 'Students in Course: ' . $course->name)

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
                                    <th class="text-end">attendance</th>
                                    <th class="text-end">grades</th>
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
                                                    <a href="{{ route('attendance.') }}" class="btn btn-sm bg-danger-light me-2">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="{{ route('attendance.show', $enrollment->student->id) }}" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                </div>
                                            @endcan
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
