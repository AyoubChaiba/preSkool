@extends('layouts.errors')

@section('title', 'Error 403')

@section('main')
    <div class="error-box">
        <h1>403</h1>
        <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> Oops! Access Denied!</h3>
        <p class="h4 font-weight-normal">You do not have permission to access this page.</p>
        <a href="{{ route('view.login') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
