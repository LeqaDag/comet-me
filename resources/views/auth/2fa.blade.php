@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.app')

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
        <div class="d-none d-lg-flex col-lg-7 col-xl-6 align-items-center">
            <div class="flex-row text-center mx-auto">
                <img src="{{asset('assets/img/pages/comet-'.$configData['style'].'.gif')}}" alt="Auth Cover Bg color" width="520" class="img-fluid authentication-cover-img" 
                data-app-light-img="pages/comet-light.gif" data-app-dark-img="pages/login-dark.png">
                <div class="mx-auto">
                    <h3>Discover The Power of Our System🥳</h3>
                </div>
            </div>
        </div>
        <!-- /Left Text -->
        <!-- Login -->
        <div class="d-flex col-12 col-lg-5 col-xl-6 align-items-center authentication-bg p-sm-5 p-4">
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

                <form method="POST" action="{{ route('2fa.post') }}">
                    @csrf
                
                    <p class="text-center">We sent code to your email :
                        {{ 
                            Auth::guard('user')->user()->email
                        }}
                    </p>

                    @if ($message = Session::get('success'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                    <strong>{{ $message }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                    <strong>{{ $message }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>

                        <div class="col-md-6">
                            <input id="code" type="number" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autocomplete="code" autofocus>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <a class="btn btn-link" href="{{ route('2fa.resend') }}">
                                Resend Code?
                            </a>
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