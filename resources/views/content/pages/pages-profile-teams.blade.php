@extends('layouts/layoutMaster')

@section('title', 'User Profile - Teams')

<!-- Page -->
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
@endsection

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">User Profile /</span> Teams
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
      <li class="nav-item"><a class="nav-link" href="{{url('pages/profile-user')}}"><i class='bx bx-user'></i> Profile</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class='bx bx-group'></i> Teams</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/profile-connections')}}"><i class='bx bx-link-alt'></i> Connections</a></li>
    </ul>
  </div>
</div>
<!--/ Navbar pills -->

<?php
  $user_id = Auth::guard('user')->user()->id;
  $allUsers = App\Models\User::where('type', 1)
    ->where('is_admin', 0)
    ->where('id', '!=', $user_id)
    ->get();
?>
<!-- Connection Cards -->
<div class="row g-4">
  @if(count($allUsers))
  @foreach($allUsers as $user)
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
      <div class="card-body text-center">
        <div class="dropdown btn-pinned">
          <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="javascript:void(0);">Share connection</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Block connection</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="javascript:void(0);">Delete</a></li>
          </ul>
        </div>
        <div class="mx-auto mb-3">
          @if($user->image == "")
            @if($user->gender == "ذكر")
              <img src='/users/profile/male.jpg' alt="Avatar Image"  class="rounded-circle w-px-100">
            @else
              <img src='/users/profile/female.jpg'  alt="Avatar Image" class="rounded-circle w-px-100" >
            @endif
            @else
              <img class="rounded-circle w-px-100"  alt="Avatar Image" 
              src="{{url('users/profile/'.@$user->image)}}">
          @endif
        </div>
        <h5 class="mb-1 card-title">{{$user->fname}} {{$user->lname}}</h5>
        <span>Trader</span>
        <div class="d-flex align-items-center justify-content-center my-3 gap-2">
          <a href="javascript:;" class="me-1"><span class="badge bg-label-danger">Financial Analyst</span></a>
          <a href="javascript:;"><span class="badge bg-label-info">Stock Analyst</span></a>
        </div>
        <?php
        $allRecommendationsUser =  App\Models\EmployeeRecommendation::where('user_id', $user->id)
          ->get()->count();

        $allUsers = App\Models\User::where('type', 1)
          ->where('is_admin', 0)
          ->where('id', '!=', $user->id)
          ->get();

        $successRecommendations = App\Models\EmployeeCalculation::where('user_id', $user->id)
          ->where('is_profit', 1)
          ->get()->count();

        $failedRecommendations = App\Models\EmployeeCalculation::where('user_id', $user->id)
          ->where('is_profit', 0)
          ->get()->count();
        ?>
        <div class="d-flex align-items-center justify-content-around my-4 py-2">
          <div>
            <h4 class="mb-1">
              @if($allRecommendationsUser)
                {{$allRecommendationsUser}}
              @else
                0
              @endif
            </h4>
            <span>Recommendations</span>
          </div>
          <div>
            <h4 class="mb-1">
              @if($successRecommendations)
                {{$successRecommendations}}
              @else
                0
              @endif
            </h4>
            <span>Successed</span>
          </div>
          <div>
            <h4 class="mb-1">
              @if($failedRecommendations)
                {{$failedRecommendations}}
              @else
                0
              @endif
            </h4>
            <span>Failed</span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-center">
          <a href="javascript:;" class="btn btn-label-primary d-flex align-items-center me-3">
            <i class="bx bx-phone me-1"></i>0{{$user->phone}}
          </a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  @endif

</div>
<!--/ Connection Cards -->
@endsection