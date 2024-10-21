@extends('layouts.app')

@section('title', 'Create Section')

@section("style")
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('main')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Create Section</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('section.index') }}">Sections</a></li>
                            <li class="breadcrumb-item active">Create Section</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <form id="createSectionForm" method="POST" action="{{ route('section.store') }}">
                            @csrf

                            <div class="row">

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" placeholder="Enter Name" >
                                        <div class="invalid-feedback section_name_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Section Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="section_name" placeholder="Enter Section Name" >
                                        <div class="invalid-feedback section_name_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Select Class </label>
                                        <select class="form-control" name="class_id">
                                            <option value="" disabled selected>Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback class_id_error"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class Capacity <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="capacity" placeholder="Enter Capacity">
                                        <div class="invalid-feedback capacity_error"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Create Section</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            function clearValidationErrors() {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function showValidationErrors(errors) {
                $.each(errors, function(key, value) {
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                    $('.' + key + '_error').text(value[0]);
                });
            }

            $('#createSectionForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Creating...',
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
                            text: 'Section created successfully!',
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            showValidationErrors(errors);
                        }
                        Swal.close();
                    },
                    complete: function() {
                        Swal.close();
                    }
                });
            });

            $('input, select').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endsection
