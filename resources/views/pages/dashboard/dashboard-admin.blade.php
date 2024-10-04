@extends("layouts.app")

@section("title", "Admin Dashboard")

@section("style")

@endsection

@section("main")
<div class="content container-fluid">

    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome Admin!</h3>
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
                            <h6>Students</h6>
                            <h3>{{ $studentsCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/dash-icon-01.svg")}}" alt="Dashboard Icon">
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
                            <h6>teachers</h6>
                            <h3>{{ $teachersCount}}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/5238535.png")}}" class="img-fluid" alt="Dashboard Icon">
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
                            <h6>Courses</h6>
                            <h3>{{ $coursesCount }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/teacher-icon-02.svg")}}" alt="Dashboard Icon">
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
                            <h6>Revenue</h6>
                            <h3>${{ number_format($totalFees, 2) }}</h3>
                        </div>
                        <div class="db-icon">
                            <img src="{{asset("assets/img/icons/dash-icon-04.svg")}}" alt="Dashboard Icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js-content")
    <script src="{{asset("assets/plugins/apexchart/apexcharts.min.js")}}"></script>
    <script src="{{asset("assets/plugins/apexchart/chart-data.js")}}"></script>
@endsection
