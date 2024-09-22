@extends('layouts.auth')

@section('title')
    login
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
                        <h1>Welcome to Preskool</h1>
                        <p class="account-subtitle">Need an account? <a href="{{ route('view.register') }}">Sign Up</a></p>
                        <h2>Sign in</h2>

                        <form id="login-form" action="{{ route('auth.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="form-group mb-2">
                                    <label>Username <span class="login-danger">*</span></label>
                                    <input class="form-control" type="text" name="email" id="email">
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <span class="error-message text-danger" id="email-error"></span>
                            </div>
                            <div class="mb-3">
                                <div class="form-group mb-2">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input class="form-control pass-input" type="password" name="password" id="password">
                                    <span class="profile-views feather-eye toggle-password"></span>
                                </div>
                                <span class="error-message text-danger" id="password-error"></span>
                            </div>
                            <div class="forgotpass">
                                <div class="remember-me">
                                    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                        <input type="checkbox" name="remember">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <a href="{{ route('view.forgotPassword') }}">Forgot Password?</a>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit" id="login-btn">Login</button>
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
    $(document).ready(function () {
        $('#login-form').on('submit', function (e) {
            e.preventDefault();

            let email = $('#email').val();
            let password = $('#password').val();
            let token = $('input[name="_token"]').val();
            let remember = $('input[name="remember"]').is(':checked');

            $('#email-error').text('');
            $('#password-error').text('');

            $('#login-btn').attr('disabled', true).text('Logging in...');

            $.ajax({
                url: "{{ route('auth.login') }}",
                type: 'POST',
                data: {
                    _token: token,
                    email: email,
                    password: password,
                    remember: remember
                },
                success: function (response) {
                    window.location.href = response.redirect_url;
                    $('#login-btn').attr('disabled', false).text('Login');
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors?.email) {
                        $('#email-error').text(errors.email[0]);
                    }
                    if (errors?.password) {
                        $('#password-error').text(errors.password[0]);
                    }

                    $('#login-btn').attr('disabled', false).text('Login');
                }
            });
        });
    });
</script>
@endsection
