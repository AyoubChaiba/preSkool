@extends('layouts.app')

@section('title', 'Edit User')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')

    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Admin</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route("admin.index") }}">Admin</a></li>
                            <li class="breadcrumb-item active">Edit Admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">

                        <form id="adminForm" action="{{ route('admin.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Full Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" value="{{ $admin->name }}" placeholder="Enter full Name" required>
                                        <span class="text-danger error-text name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" value="{{ $admin->email }}" placeholder="Enter Email" required>
                                        <span class="text-danger error-text email_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Password</label>
                                        <input class="form-control" type="password" name="password" placeholder="Enter Password (optional)">
                                        <span class="text-danger error-text password_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Role <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="role" required>
                                            <option value="">Please Select Role</option>
                                            <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="student" {{ $admin->role == 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="teacher" {{ $admin->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                            <option value="parent" {{ $admin->role == 'parent' ? 'selected' : '' }}>Parent</option>
                                        </select>
                                        <span class="text-danger error-text role_error"></span>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select').select2();

            function clearValidationErrors() {
                $('.error-text').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#adminForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#adminForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Admin updated successfully!',
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
