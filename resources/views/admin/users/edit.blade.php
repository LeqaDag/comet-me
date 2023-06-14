@extends('layouts/layoutMaster')

@section('title', 'edit user')

@include('layouts.all')

<style>
    label, input {
    display: block;
}

label {
    margin-top: 20px;
}
</style>
@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$user->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif


<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('user.update', $user->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    @if($user->image == "")
                    
                    @if($user->gender == "male")
                        <img src='/users/profile/male.jpg' alt="user-avatar" 
                            class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                    @else
                        <img src='/assets/images/female.png' alt="user-avatar" 
                            class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                    @endif
                    @else
                        <img src="{{url('users/profile/'.$user->image)}}" alt="user-avatar" 
                            class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>
                    @endif
            
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input type="file" id="upload" name="image" class="account-file-input" 
                                hidden accept="image/png, image/jpeg" />
                        </label>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" value="{{$user->name}}"
                            class="form-control">
                            @if ($errors->has('name'))
                                <span class="error">{{ $errors->first('name') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Email</label>
                            <input type="text" name="email" class="form-control"
                                value="{{$user->email}}">
                            @if ($errors->has('email'))
                                <span class="error">{{ $errors->first('email') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="number" name="phone" class="form-control"
                                value="{{$user->phone}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Role</label>
                            <select name="user_type_id" class="form-control">
                                <option disabled selected>
                                    {{$user->UserType->name}}
                                </option>
                                @foreach($userTypes as $userType)
                                <option value="{{$userType->id}}">{{$userType->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Change Password</label>
                            <input type="password" name="password" class="form-control"
                                >
                            @if ($errors->has('password'))
                                <span class="error">{{ $errors->first('password') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Confirm password</label>
                            <input type="password" name="confirm-password" class="form-control"
                                >
                        </fieldset>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection