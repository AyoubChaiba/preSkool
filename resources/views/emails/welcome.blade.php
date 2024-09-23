@extends('layouts.email')


@section("content")
    <div class="container mt-5">
        <h1 class="text-center">Welcome, {{ $user->name }}!</h1>
        <p class="text-center">We are glad to have you on board. Below are your login details:</p>
        <div class="card">
            <div class="card-body">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Role:</strong> {{ $user->role }}</p>
                <p class="mt-3">Feel free to change your password after logging in.</p>
            </div>
        </div>
        <p class="text-center mt-4">Best regards,<br>{{ config('app.name') }}</p>
    </div>
@endsection
