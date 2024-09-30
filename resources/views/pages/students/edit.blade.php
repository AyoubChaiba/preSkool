@extends('layouts.app')

@section('title', 'Edit Student')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap-datetimepicker.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}" />
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Student</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                            <li class="breadcrumb-item active">Edit Student</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="studentForm" method="POST" action="{{ route('student.update', $student->id) }}">
                            @csrf
                            @method('PUT') 
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Full Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" value="{{ $student->user->name }}" placeholder="Enter full Name">
                                        <span class="text-danger error-text name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" value="{{ $student->user->email }}" placeholder="Enter Email">
                                        <span class="text-danger error-text email_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Parent <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="parent_id">
                                            <option value="">Select Parent</option>
                                            @foreach($parents as $parent)
                                                <option value="{{ $parent->id }}" {{ $student->parent_id == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text parent_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms calendar-icon">
                                        <label>Admission Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" name="admission_date" value="{{ \Carbon\Carbon::parse($student->admission_date)->format('d-m-Y') }}" placeholder="DD-MM-YYYY">
                                        <span class="text-danger error-text admission_date_error"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/moment/moment.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap-datetimepicker.min.js") }}"></script>

    <script>
        $(document).ready(function() {
            function clearValidationErrors() {
                $('.error-text').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Student updated successfully!',
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.error;
                        showValidationErrors(errors);
                        Swal.close();
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            });

            $('input, select').on('input change', function() {
                $(this).next('.error-text').text('');
            });
        });
    </script>
@endsection
