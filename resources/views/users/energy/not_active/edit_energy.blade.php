@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit household')

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
    <span class="text-muted fw-light">Edit </span> {{$energyUser->Household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-meter.update', $energyUser->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Number</label>
                            <input type="text" class="form-control" name="meter_number"
                                value="{{$energyUser->meter_number}}"> 
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Daily limit</label> 
                            <input type="text" class="form-control" name="daily_limit"
                                value="{{$energyUser->daily_limit}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation date</label>
                            <input type="date" class="form-control" name="installation_date" 
                            value="{{$energyUser->installation_date}}"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Active</label> 
                            <select name='meter_active' class="form-control">
                                <option selected disabled>
                                    {{$energyUser->meter_active}}
                                </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' for="region_id">Meter Case</label>
                            <select name='meter_case_id' name="meter_case_id " class="form-control">
                                <option disabled selected>
                                    {{$energyUser->MeterCase->meter_case_name_english}}
                                </option>
                                @foreach($meterCases as $meterCase)
                                    <option value="{{$meterCase->id}}">
                                        {{$meterCase->meter_case_name_english}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Vendor Name</label> 
                            <select name='vendor_username_id' class="form-control">
                                <option selected disabled>
                                    @if($vendor)
                                    {{$vendor->name}}
                                    @else
                                    Choose one...
                                    @endif
                                </option>
                                @foreach($communityVendors as $vendor)
                                    <option value="{{$vendor->vendor_username_id}}">
                                        {{$vendor->name}}
                                    </option>
                                @endforeach
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