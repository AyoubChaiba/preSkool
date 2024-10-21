@extends('layouts.app')

@section('title', 'Edit Fees')

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
                        <h3 class="page-title">Edit Fees</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li>
                            <li class="breadcrumb-item active">Edit Fees</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form id="feesForm" method="POST" action="{{ route('fees.update', $fee->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Student <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="student_id">
                                            <option value="">Select Student</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ $fee->student_id == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text student_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Fee Type <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="status">
                                            <option value="">Select Fee Type</option>
                                            <option value="paid" {{ $fee->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="pending" {{ $fee->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        <span class="text-danger error-text status_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms">
                                        <label>Amount <span class="login-danger">*</span></label>
                                        <input class="form-control" type="number" name="amount" value="{{ $fee->amount }}" placeholder="Enter Amount" step="0.01" min="0">
                                        <span class="text-danger error-text amount_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group local-forms calendar-icon">
                                        <label>Due Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker" type="text" name="payment_date" value="{{ $fee->payment_date }}" placeholder="DD-MM-YYYY">
                                        <span class="text-danger error-text due_date_error"></span>
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

            $('#feesForm').on('submit', function(e) {
                e.preventDefault();
                clearValidationErrors();

                Swal.fire({
                    title: 'Loading...',
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
                            text: 'Fees updated successfully!',
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
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
