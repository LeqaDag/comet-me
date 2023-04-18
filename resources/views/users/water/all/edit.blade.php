@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit water user')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label {
        margin-top: 20px;
    }
    .headingLabel {
        font-size:18px;
        font-weight: bold;
    }
</style>
@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$h2oUser->Household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-water.update', $h2oUser->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" class="form-control" >
                                @if($h2oUser->Community)
                                <option value="{{$h2oUser->Community->id}}" disabled selected>
                                    {{$h2oUser->Community->english_name}}
                                </option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water User</label>
                            <select name="household_id" class="form-control" >
                                @if($h2oUser->Household)
                                <option value="{{$h2oUser->Household->id}}" disabled selected>
                                    {{$h2oUser->Household->english_name}}
                                </option>
                                @foreach($households as $household)
                                <option value="{{$household->id}}">
                                    {{$household->english_name}}
                                </option>
                                @endforeach
                                @else
                                @foreach($households as $household)
                                <option value="{{$household->id}}">
                                    {{$household->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>H2O System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of H2O</label>
                            <input type="number" name="number_of_h20" 
                            value="{{$h2oUser->number_of_h20}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Status</label>
                            <select name="h2o_status_id" class="form-control" >
                                @if($h2oUser->H2oStatus)
                                    <option disabled selected>
                                        {{$h2oUser->H2oStatus->status}}
                                    </option>
                                    @foreach($h2oStatuses as $h2oStatus)
                                    <option value="{{$h2oStatus->id}}">
                                        {{$h2oStatus->status}}
                                    </option>
                                    @endforeach
                                @else
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of BSF</label>
                            <input type="number" name="number_of_bsf" 
                            value="{{$h2oUser->number_of_bsf}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSF Status</label>
                            <select name="bsf_status_id" class="form-control" >
                                @if($h2oUser->BsfStatus)
                                    <option  disabled selected>
                                        {{$h2oUser->BsfStatus->name}}
                                    </option>
                                    @foreach($bsfStatuses as $bsfStatus)
                                    <option value="{{$bsfStatus->id}}">
                                        {{$bsfStatus->name}}
                                    </option>
                                    @endforeach
                                @else
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Request Date</label>
                            <input type="date" name="h2o_request_date" 
                            value="{{$h2oUser->h2o_request_date}}"
                                class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" 
                            value="{{$h2oUser->installation_year}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Date</label>
                                <input type="date" name="h2o_installation_date" 
                                value="{{$h2oUser->h2o_installation_date}}"
                                    class="form-control">
                            </fieldset>
                        </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Grid System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large</label>
                            @if($gridUser)
                            <input type="number" name="grid_integration_large" 
                            value="{{$gridUser->grid_integration_large}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_large" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large Date</label>
                            @if($gridUser)
                            <input type="date" name="large_date" 
                            value="{{$gridUser->large_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="large_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small</label>
                            @if($gridUser)
                            <input type="number" name="grid_integration_small" 
                            value="{{$gridUser->grid_integration_small}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_small"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small Date</label>
                            @if($gridUser)
                            <input type="date" name="small_date" 
                            value="{{$gridUser->small_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="small_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Request Date</label>
                            @if($gridUser)
                            <input type="date" name="request_date" 
                            value="{{$gridUser->request_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="request_date" 
                            class="form-control">

                            @endif
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Confirmation</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Delivery</label>
                            <select name="is_delivery" class="form-control">
                                @if($gridUser)
                                    <option disabled selected>{{$gridUser->is_delivery}}</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                @else

                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Paid</label>
                            <select name="is_paid" class="form-control">
                            @if($gridUser)
                                <option disabled selected>{{$gridUser->is_paid}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="NA">NA</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Complete</label>
                            <select name="is_complete" class="form-control">
                            @if($gridUser)
                                <option disabled selected>{{$gridUser->is_complete}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                @endif
                            </select>
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