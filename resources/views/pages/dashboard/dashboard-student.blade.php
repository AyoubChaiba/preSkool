@extends('layouts.app')

@section("title", "Stuent Dashboard")

@section("style")
    <link rel="stylesheet" href="{{asset("assets/plugins/simple-calendar/simple-calendar.css")}}">
@endsection

@section('main')
<div class="content container-fluid">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome {{Auth::user()->name}}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . ".dashboard")}}">Home</a></li>
                        <li class="breadcrumb-item active">{{Auth::user()->role}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>All Courses</h6>
                            <h3>{{ $coursesCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/teacher-icon-01.svg")}}" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Total fees</h6>
                            <h3>${{ number_format($totalFees, 2) }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/dash-icon-04.svg")}}" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Pending Fees</h6>
                            <h3>${{ number_format($pendingFees, 2) }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{ asset('assets/img/icons/dash-icon-04.svg') }}" alt="Pending Salary Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
@endsection

@section('js-content')
    <script src="{{ asset("assets/plugins/apexchart/apexcharts.min.js") }}"></script>
    <script src=" {{ asset("assets/plugins/apexchart/chart-data.js") }}"></script>
    <script src="{{asset("assets/js/calander.js")}}"></script>
    <script src="{{asset("assets/js/circle-progress.min.js")}}"></script>
@endsection
