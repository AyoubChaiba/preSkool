@extends('layouts.errors')

@section('title', 'Error 404')

@section('main')
    <div class="error-box">
        <h1>404</h1>
        <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> Oops! Page not found!</h3>
        <p class="h4 font-weight-normal">The page you requested was not found.</p>
        <a href="{{ route("view.login") }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
