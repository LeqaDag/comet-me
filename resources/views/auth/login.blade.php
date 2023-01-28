@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Login')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
<div class="authentication-wrapper authentication-cover">
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
      <div class="flex-row text-center mx-auto">
        <img src="{{asset('assets/img/pages/comet-'.$configData['style'].'.gif')}}" alt="Auth Cover Bg color" width="520" class="img-fluid authentication-cover-img" 
        data-app-light-img="pages/comet-light.gif" data-app-dark-img="pages/login-dark.png">
        <div class="mx-auto">
          <h3>Discover The Powerful of Our System🥳</h3>
          <p>
           
          </p>
        </div>
      </div>
    </div>
    <!-- /Left Text -->
    <!-- Login -->
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
      <div class="w-px-400 mx-auto">
        <!-- Logo -->
        <div class="app-brand mb-4">
          <a href="{{url('/')}}" class="app-brand-link gap-2 mb-2">
          <img width=50 type="image/x-icon" src="{{('/logo.jpg')}}">
            <span class="app-brand-text demo h3 mb-0 fw-bold">{{config('variables.templateName')}}</span>
          </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-2">Welcome to {{config('variables.templateName')}}! 👋</h4>
        <p class="mb-4">Please sign-in to your account and start the adventure</p>

        <form id="formAuthentication" class="mb-3" method="POST" 
            action="{{ route('login') }}">
        @csrf
          <div class="form-group">
            <label class="label">Email</label>
            <div class="input-group">
              <input id="email" type="email"
                class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>
              
            </div>
          </div>
          <div class="form-group">
            <label class="label">Password</label>
            <div class="input-group">
              <input id="password" type="password"
                  class="form-control @error('password') is-invalid @enderror" name="password"
                  required autocomplete="current-password">
             
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary submit-btn btn-block">
              {{ __('Login') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
