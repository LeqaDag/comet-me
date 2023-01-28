@extends('layouts/layoutMaster')

@section('title', 'User Profile - Profile')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}">
@endsection

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
@endsection


@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">User Profile /</span> Profile
</h4>


<!-- Header -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="user-profile-header-banner">
        <img src="{{asset('assets/img/pages/profile-banner.png')}}" alt="Banner image" class="rounded-top">
      </div>
      <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
          @if(Auth::guard('user')->user()->image == "")
            @if(Auth::guard('user')->user()->gender == "ذكر")
              <img src='/users/profile/male.jpg'  alt="user image" 
                class="d-block h-auto ms-0 ms-sm-4 rounded-3 user-profile-img" >
            @else
              <img src='/users/profile/female.jpg'  alt="user image" 
                class="d-block h-auto ms-0 ms-sm-4 rounded-3 user-profile-img" >
            @endif
            @else
            <img  alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded-3 user-profile-img"
              src="{{url('users/profile/'.@Auth::guard('user')->user()->image)}}">
          @endif
        </div>
        <div class="flex-grow-1 mt-3 mt-sm-5">
          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
            <div class="user-profile-info">
              <h4>{{Auth::guard('user')->user()->fname}} {{Auth::guard('user')->user()->lname}}</h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                <li class="list-inline-item fw-semibold">
                  <i class='bx bx-pen'></i> Trader & Financial Manager
                </li>
                <li class="list-inline-item fw-semibold">
                  <i class='bx bx-mobile'></i> 0{{Auth::guard('user')->user()->phone}}
                </li>
                <li class="list-inline-item fw-semibold">
                  <i class='bx bx-calendar-alt'></i> 
                  Joined {{ \Carbon\Carbon::parse(Auth::guard('user')->user()->created_at)->format('d/m/Y')}}
                </li>
              </ul>
            </div>
            <a href="javascript:void(0)" class="btn btn-primary text-nowrap">
              <i class='bx bx-user-check'></i> Connected
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Header -->

<!-- Navbar pills -->
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class='bx bx-user'></i> Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/profile-teams')}}"><i class='bx bx-group'></i> Teams</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/profile-connections')}}"><i class='bx bx-link-alt'></i> Connections</a></li>
    </ul>
  </div>
</div>
<!--/ Navbar pills -->

<!-- User Profile Content -->
<div class="row">
  <div class="col-xl-4 col-lg-5 col-md-5">
    <!-- About User -->
    <div class="card mb-4">
      <div class="card-body">
        <small class="text-muted text-uppercase">About</small>
        <ul class="list-unstyled mb-4 mt-3">
          <li class="d-flex align-items-center mb-3">
            <i class="bx bx-user"></i>
            <span class="fw-semibold mx-2">Full Name:</span> 
            <span>{{Auth::guard('user')->user()->fname}} {{Auth::guard('user')->user()->lname}}</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="bx bx-check"></i>
            <span class="fw-semibold mx-2">Status:</span> 
            <span>Active</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="bx bx-star"></i>
            <span class="fw-semibold mx-2">Role:</span> 
            <span>Trader & Financial Manager</span>
          </li>
        </ul>
        <small class="text-muted text-uppercase">Contacts</small>
        <ul class="list-unstyled mb-4 mt-3">
          <li class="d-flex align-items-center mb-3">
            <i class="bx bx-phone"></i>
            <span class="fw-semibold mx-2">Contact:</span> 
            <span>0{{Auth::guard('user')->user()->phone}}</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="bx bx-envelope"></i>
            <span class="fw-semibold mx-2">Email:</span> 
            <span>{{Auth::guard('user')->user()->email}}</span>
          </li>
        </ul>
      </div>
    </div>
    <!--/ About User -->
    
  </div>
  <div class="col-xl-8 col-lg-7 col-md-7">
   
    <div class="row">
      <!-- Profile Overview -->
      <div class="card col-lg-12 col-xl-6 mb-4">
        <div class="card-body">
          <small class="text-muted text-uppercase">Overview</small>
          <ul class="list-unstyled mt-3 mb-0">
            <li class="d-flex align-items-center mb-3">
              <i class="bx bx-customize"></i>
              <span class="fw-semibold mx-2">All Recommendations:</span> 
              <?php
                $user_id = Auth::guard('user')->user()->id;
                $allRecommendationsUser =  App\Models\EmployeeRecommendation::where('user_id', $user_id)
                  ->get()->count();

                $allUsers = App\Models\User::where('type', 1)
                  ->where('is_admin', 0)
                  ->where('id', '!=', $user_id)
                  ->get();

                $successRecommendations = App\Models\EmployeeCalculation::where('user_id', $user_id)
                  ->where('is_profit', 1)
                  ->get()->count();

                $failedRecommendations = App\Models\EmployeeCalculation::where('user_id', $user_id)
                  ->where('is_profit', 0)
                  ->get()->count();
              ?>
              <span>
                @if($allRecommendationsUser)
                  {{$allRecommendationsUser}}
                @else
                  0
                @endif
              </span>
            </li>
            <li class="d-flex align-items-center mb-3">
              <i class='bx bx-check'></i>
              <span class="fw-semibold mx-2">Success Recommendations:</span> 
              <span>
                @if($successRecommendations)
                  {{$successRecommendations}}
                @else
                  0
                @endif
              </span>
            </li>
            <li class="d-flex align-items-center mb-3">
              <i class='bx bx-window-close'></i>
              <span class="fw-semibold mx-2">Failed Recommendations:</span> 
              <span>
                @if($failedRecommendations)
                  {{$failedRecommendations}}
                @else
                  0
                @endif
              </span>
            </li>
            <li class="d-flex align-items-center">
              <i class="bx bx-user"></i>
              <span class="fw-semibold mx-2">Other Traders:</span> 
              <span>
                @if(count($allUsers))
                  {{count($allUsers)}}
                @else
                  0
                @endif
              </span>
            </li>
          </ul>
        </div>
      </div>
      <!--/ Profile Overview -->
      <!-- Connections -->
      <div class="col-lg-12 col-xl-6">
        <div class="card mb-4">
          <div class="card-header align-items-center">
            <h5 class="card-action-title mb-0">All Traders & Financial Managers</h5>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              @if(count($allUsers))
              @foreach ($allUsers as $user)
              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">

                      @if($user->image == "")
                      
                      @if($user->gender == "ذكر")
                        <img src='/users/profile/male.jpg' alt="Avatar"  class="rounded-circle">
                      @else
                        <img src='/users/profile/female.jpg'  alt="Avatar" class="rounded-circle" >
                      @endif
                      @else
                      <img class="rounded-circle"  alt="Avatar" 
                        src="{{url('users/profile/'.@$user->image)}}">
                      @endif


                    </div>
                    <div class="me-2">
                      <h6 class="mb-0">{{$user->fname}} {{$user->lname}}</h6>
                      <small class="text-muted">
                        <?php $allRecommendations = App\Models\EmployeeRecommendation::where('user_id', $user->id)
                          ->get()->count();?>
                         @if($allRecommendations)
                         Total Recommendations: {{$allRecommendations}}
                        @else
                        Total Recommendations:  0
                        @endif
                      </small>
                    </div>
                  </div>
                  <div class="ms-auto">
                    <button class="btn btn-label-primary p-1 btn-sm"><i class="bx bx-user"></i></button>
                  </div>
                </div>
              </li>
              @endforeach
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!--/ Connections -->
   
    </div>

  </div>
</div>
<!--/ User Profile Content -->
@endsection
