@extends('layouts.auth')

@section('title')
forgot password
@endsection

@section('main')
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left">
                    <img class="img-fluid" src="{{ asset('assets/img/login.png')}}" alt="Logo">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Reset Password</h1>
                        <p class="account-subtitle">Let Us Help You</p>

                        <form action="login.html">
                            <div class="form-group">
                                <label>Enter your registered email address <span class="login-danger">*</span></label>
                                <input class="form-control" type="text">
                                <span class="profile-views"><i class="fas fa-envelope"></i></span>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Reset My Password</button>
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-primary primary-reset btn-block" type="submit">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
