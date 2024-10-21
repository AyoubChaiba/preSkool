@extends('layouts.app')

@section('title', 'Student Exams')

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
                        <h3 class="page-title">Exams & Grades</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Exams</a></li>
                            <li class="breadcrumb-item active">Student's Grades</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Exams & Grades</h3>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 table-striped" id="exams">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Exam Name</th>
                                        <th>Exam Date</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grades as $grade)
                                        <tr>
                                            <td>{{ $grade->id }}</td>
                                            <td>{{ $grade->exam->exam_name }}</td>
                                            <td>{{ $grade->exam->exam_date }}</td>
                                            <td>{{ $grade->subject->name }}</td>
                                            <td>{{ $grade->grade }}</td>
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
        $('#exams').DataTable();
    });
    </script>
@endsection
