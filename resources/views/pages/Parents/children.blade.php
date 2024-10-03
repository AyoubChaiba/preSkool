@extends('layouts.app')

@section('title', 'Children List')

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
                        <h3 class="page-title">Children</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Children</li>
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
                                    <h3 class="page-title">Children List</h3>
                                </div>
                                @can("view")
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('child.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Child</a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Admission date</th>
                                        <th>Course</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($children as $child)
                                        <tr>
                                            <td>{{ $child->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    {{ $child->user->name }}
                                                </h2>
                                            </td>
                                            <td>
                                                {{ $child->admission_date }}
                                            </td>
                                            <td>
                                                {{ $child->enrollments->count() }}
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route("courses.child", $child->id) }}" class="btn btn-sm bg-info-light">
                                                        <i class="fas fa-book-open"></i>
                                                    </a>
                                                </div>
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
@endsection
