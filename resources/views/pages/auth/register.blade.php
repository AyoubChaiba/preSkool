@extends('layouts.auth')

@section('title')
    Register
@endsection

@section('main')
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left">
                    <img class="img-fluid" src="{{ asset('assets/img/login.png') }}" alt="Logo">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Sign Up</h1>
                        <p class="account-subtitle">Enter details to create your account</p>
                        <form id="registerForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group mb-1">
                                    <label>Role <span class="login-danger">*</span></label>
                                    <select class="form-control" name="role" id="role">
                                        <option value="">Select your role</option>
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="parent">Parent</option>
                                    </select>
                                </div>
                                <span class="error-message text-danger" id="role-error"></span>
                            </div>
                            <div class="mb-3">
                                <div class="form-group mb-1">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" placeholder="Enter your name">
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <span class="error-message text-danger" id="name-error"></span>
                            </div>
                            <div class="mb-3">
                                <div class="form-group mb-1">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" id="email" placeholder="Enter your email">
                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                </div>
                                <span class="error-message text-danger" id="email-error"></span>
                            </div>
                            <div class="mb-3">
                                <div class="form-group mb-1">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input class="form-control pass-input" type="password" name="password" id="password" placeholder="Enter your password">
                                    <span class="profile-views feather-eye toggle-password"></span>
                                </div>
                                <span class="error-message text-danger" id="password-error"></span>
                            </div>
                            <div>
                                <div class="form-group ">
                                    <label>Confirm Password <span class="login-danger">*</span></label>
                                    <input class="form-control pass-confirm" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password">
                                    <span class="profile-views feather-eye reg-toggle-password"></span>
                                </div>
                                <span class="error-message text-danger" id="password-error"></span>
                            </div>
                            <div class="dont-have">Already Registered? <a href="{{ route('view.login') }}">Login</a></div>
                            <div class="form-group mb-0">
                                <button class="btn btn-primary btn-block" type="submit">Register</button>
                            </div>
                        </form>

                        <div class="login-or">
                            <span class="or-line"></span>
                            <span class="span-or">or</span>
                        </div>

                        <div class="social-login">
                            <a href="#"><i class="fab fa-google-plus-g"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-content')
<script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(event) {
            event.preventDefault();

            var formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
                role: $('#role').val(),
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ route('auth.register') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    window.location.href = "{{ route('view.login') }}";
                },
                error: function(response) {
                    $('.error-message').html('');

                    if (response.responseJSON && response.responseJSON.error) {
                        if (response.responseJSON.error.name) {
                            $('#name-error').html(response.responseJSON.error.name[0]);
                        }
                        if (response.responseJSON.error.email) {
                            $('#email-error').html(response.responseJSON.error.email[0]);
                        }
                        if (response.responseJSON.error.password) {
                            $('#password-error').html(response.responseJSON.error.password[0]);
                        }
                        if (response.responseJSON.error.role) {
                            $('#role-error').html(response.responseJSON.error.role[0]);
                        }
                    }
                }
            });
        });
    });
</script>
@endsection
