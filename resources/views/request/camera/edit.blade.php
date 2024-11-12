@extends('layouts/layoutMaster')

@section('title', 'edit water request')

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
    <span class="text-muted fw-light">Edit </span> 
        {{$requestedCamera->Community->english_name}} - 
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('camera-request.update', $requestedCamera->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
              
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" name="community_id" disabled>
                                <option disabled selected>{{$requestedCamera->Community->english_name}}</option>
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Request Date</label>
                            <input type="date" name="date" class="form-control" 
                                value="{{$requestedCamera->date}}">
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Status of request</label>
                            <select name="camera_request_status_id" 
                                class="selectpicker form-control" data-live-search="true">
                                <option disabled selected>{{$requestedCamera->CameraRequestStatus->name}}</option>
                                @foreach($requestStatuses as $requestStatus) 
                                    <option value="{{$requestStatus->id}}">
                                        {{$requestStatus->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Who took the request?</label>
                            <select name="user_id" class="selectpicker form-control"data-live-search="true">
                                @if($requestedCamera->user_id)
                                    <option disabled selected>{{$requestedCamera->User->name}}</option>
                                    @foreach($users as $user) 
                                    <option value="{{$user->id}}">
                                        {{$user->name}}
                                    </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($users as $user) 
                                        <option value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Referred by</label>
                            <textarea name="referred_by" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$requestedCamera->referred_by}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$requestedCamera->notes}}
                            </textarea>
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