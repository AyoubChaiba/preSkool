@extends('layouts.app')

@section('title', 'Timetable Sections')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .no-schedule {
            color: #6c757d;
            text-align: center;
        }
    </style>
@endsection

@section('main')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Timetable</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('timetables.index', $class->id) }}">Timetable</a></li>
                        <li class="breadcrumb-item active">All Timetable</li>
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
                                <h3 class="page-title">Timetable for {{ $class->class_name }}</h3>
                            </div>
                            @can("viewAny", Auth::user())
                                <div class="col-auto text-end">
                                    <a href="{{ route('timetables.create', $class->id) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover datatable" id="timetableTable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Class</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Scheduled Subjects</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $day)
                                    <tr>
                                        <td>{{ $class->class_name }}</td>
                                        <td>{{ $day }}</td>
                                        <td>
                                            @if($timetable[$day]->isEmpty())
                                                <span class="no-schedule">No subjects scheduled</span>
                                            @else
                                                <ul class="list-group">
                                                    @foreach($timetable[$day] as $entry)
                                                        <li class="list-group-item">
                                                            <div>
                                                                <strong>{{ $entry->subject->subject_name }}</strong>
                                                                <span class="text-muted"> | {{ $entry->teacher->name }}</span>
                                                                @php
                                                                    $start_time = \Carbon\Carbon::parse($entry->start_time)->format('h:i A');
                                                                    $end_time = \Carbon\Carbon::parse($entry->end_time)->format('h:i A');
                                                                @endphp
                                                                <small class="text-muted">({{ $start_time }} - {{ $end_time }})</small>
                                                                <strong>| ({{ $entry->section->name }})</strong>
                                                            </div>
                                                            @can('viewAny', Auth::user())
                                                                <div>
                                                                    <button class="btn btn-sm btn-info edit-timetable" data-id="{{ $entry->id }}">Edit</button>
                                                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $entry->id }}">Delete</button>
                                                                </div>
                                                            @endcan
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
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


            $('.btn-delete').click(function(e) {
                e.preventDefault();

                const button = $(this);
                const id = button.data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: `/timetables/${id}`,
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                button.closest('li').fadeOut();
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message || 'The timetable entry was successfully deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'There was a problem deleting the timetable entry.';
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            $('.edit-timetable').click(function() {
                const id = $(this).data('id');
                window.location.href = `/timetables/${id}/edit`;
            });
        });
    </script>
@endsection
