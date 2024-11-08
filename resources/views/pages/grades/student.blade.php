@extends('layouts.app')

@section('title', 'Student Grades')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .grade-excellent {
            background-color: #d4edda;
            color: #155724;
        }
        .grade-very-good {
            background-color: #c3e6cb;
            color: #155724;
        }
        .grade-good {
            background-color: #fff3cd;
            color: #856404;
        }
        .grade-average {
            background-color: #ffeeba;
            color: #856404;
        }
        .grade-fail {
            background-color: #f8d7da;
            color: #721c24;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin-left: 2px;
            margin-right: 2px;
        }
    </style>
@endsection

@section('main')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Student Grades</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                        <li class="breadcrumb-item active">Grades Report</li>
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
                                <h3 class="page-title">Student Grades Overview</h3>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover" id="timetableTable">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Total Exams</th>
                                    @if(isset($studentGrades[0]['exam_details']))
                                        @foreach($studentGrades[0]['exam_details'] as $examDetail)
                                            <th>{{ $examDetail['exam_name'] }}</th>
                                        @endforeach
                                    @endif
                                    <th>Total Grade</th>
                                    <th>Average Grade</th>
                                    <th>Grade Name</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentGrades as $grade)
                                    <tr class="
                                        @if($grade['average_grade'] >= 18)
                                            grade-excellent
                                        @elseif($grade['average_grade'] >= 16)
                                            grade-very-good
                                        @elseif($grade['average_grade'] >= 13)
                                            grade-good
                                        @elseif($grade['average_grade'] >= 10)
                                            grade-average
                                        @else
                                            grade-fail
                                        @endif">
                                        <td>{{ $grade['subject'] }}</td>
                                        <td>{{ $grade['total_exams'] }}</td>
                                        @foreach($grade['exam_details'] as $examDetail)
                                            <td>{{ $examDetail['grade'] ?? 'N/A' }}</td>
                                        @endforeach
                                        <td>{{ $grade['total_grade'] }}</td>
                                        <td>{{ $grade['average_grade'] }}</td>
                                        <td>{{ $grade['grade_name'] }}</td>
                                        <td>{{ $grade['comment'] }}</td>
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
@endsection
