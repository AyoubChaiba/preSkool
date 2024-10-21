@extends('layouts.app')

@section('title', 'Edit Section')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />
@endsection

@section('main')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Section</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('section.index') }}">Sections</a></li>
                            <li class="breadcrumb-item active">Edit Section</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="editSectionForm" method="POST" action="{{ route('section.update', $section->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Section Name <span class="login-danger">*</span></label>
                                        <input class="form-control" type="text" name="section_name" value="{{ old('section_name', $section->section_name) }}" placeholder="Enter Section Name">
                                        <span class="text-danger error-text section_name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class <span class="login-danger">*</span></label>
                                        <select class="form-control select2" name="class_id">
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}" {{ $class->id == $section->class_id ? 'selected' : '' }}>
                                                    {{ $class->class_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text class_id_error"></span>
                                    </div>
                                </div>


                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Class Capacity <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="capacity" value="{{ old('capacity', $section->capacity) }}"  placeholder="Enter Capacity" >
                                        <span class="text-danger error-text capacity_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Select Teacher </label>
                                        <select class="form-control" name="class_teacher_id" >
                                            <option value="" disabled selected>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}"  {{ $teacher->id == $section->class_teacher_id ? 'selected' : '' }} >
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text class_id_error"></span>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>

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

            $('#editSectionForm').on('submit', function(e) {
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
                            text: 'Section updated successfully!',
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
                $(this).next('.error-text').text('');
            });
        });
    </script>
@endsection
