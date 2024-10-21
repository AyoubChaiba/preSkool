@extends('layouts.app')

@section('title', 'Add Admin')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')

<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Add Admin</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                        <li class="breadcrumb-item active">Add Admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="adminForm" method="POST" action="{{ route('admin.store') }}">
                        @csrf
                        <div class="row">

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>Username <span class="login-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" placeholder="Enter Username">
                                    <div class="invalid-feedback username_error"></div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" placeholder="Enter Email">
                                    <div class="invalid-feedback email_error"></div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input class="form-control" type="password" name="password" placeholder="Enter Password">
                                    <div class="invalid-feedback password_error"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {

            function clearValidationErrors() {
                $('.error-text').text(''); // Clear text error messages
                $('.is-invalid').removeClass('is-invalid'); // Remove 'is-invalid' class from all inputs
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    let inputField = $('input[name="' + key + '"]'); // Select input by name
                    inputField.addClass('is-invalid'); // Add 'is-invalid' class to input
                    inputField.next('.invalid-feedback').text(value[0]); // Show error message
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
                        $('#adminForm')[0].reset(); // Reset the form
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'User created successfully!',
                        }).then(() => {
                            window.location.href = response.redirect_url; // Redirect
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors; // Laravel validation error format
                        showValidationErrors(errors); // Display the validation errors
                        Swal.close();
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            });

            // Remove validation errors on input
            $('input, select').on('input change', function() {
                $(this).removeClass('is-invalid'); // Remove 'is-invalid' class
                $(this).next('.invalid-feedback').text(''); // Clear error message
            });
        });
    </script>
@endsection
