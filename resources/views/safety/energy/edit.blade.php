@extends('layouts/layoutMaster')

@section('title', 'edit energy safety')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    .dropdown-toggle {
        height: 40px;
    }

    label {
        margin-top: 20px;
    }

    .headingLabel {
        font-size:18px;
        font-weight: bold;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> 
    @if($household)

        {{$household->english_name}}
    @else @if($public)

        {{$public->english_name}}
    @endif
    @endif

    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-safety.update', $energySafety->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <h5>General Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id[]" id="communitySafetyCheck" 
                                class="selectpicker form-control" disabled>
                                <option disabled selected>
                                    {{$energyMeter->Community->english_name}}
                                </option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Holder</label>
                            <select name="holder_id" id="selectedHolder" 
                                class="form-control" disabled>
                                <option disabled selected>
                                @if($household)

                                    {{$household->english_name}}
                                @else @if($public)

                                    {{$public->english_name}}
                                @endif
                                @endif
                                </option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Number</label>
                            <input type="text" name="meter_user" class="form-control"
                                value="{{$energyMeter->meter_number}}" disabled>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Connected Ground</label>
                            <select name='ground_connected' class="form-control">
                                <option disabled selected>
                                    {{$energyMeter->ground_connected}}
                                </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Case</label>
                            <select name="meter_case_id" class="form-control">
                                <option disabled selected>
                                    {{$energyMeter->MeterCase->meter_case_name_english}}
                                </option>
                                @foreach($meterCases as $meterCase)
                                    <option value="{{$meterCase->id}}">
                                        {{$meterCase->meter_case_name_english}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Visit Date</label>
                            <input type="date" name="visit_date" value="{{$energySafety->visit_date}}"
                                class="form-control">
                        </fieldset>
                    </div>  
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Residual Current Device (RCD)</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 0.5/Phase 0</label>
                            <input type="text" name="rcd_x_phase0" 
                            class="form-control" value="{{$energySafety->rcd_x_phase0}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 0.5/Phase 180</label>
                            <input type="text" name="rcd_x_phase1" 
                            class="form-control" value="{{$energySafety->rcd_x_phase1}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 1/Phase 0</label>
                            <input type="text" name="rcd_x1_phase0" 
                            class="form-control" value="{{$energySafety->rcd_x1_phase0}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 1/Phase 180</label>
                            <input type="text" name="rcd_x1_phase1" 
                            class="form-control" value="{{$energySafety->rcd_x1_phase1}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 5/Phase 0</label>
                            <input type="text" name="rcd_x5_phase0" 
                            class="form-control" value="{{$energySafety->rcd_x5_phase0}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>X 5/Phase 180</label>
                            <input type="text" name="rcd_x5_phase1" 
                            class="form-control" value="{{$energySafety->rcd_x5_phase1}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Loop</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PH-Loop</label>
                            <input type="text" name="ph_loop" 
                            class="form-control" value="{{$energySafety->ph_loop}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>N-Loop</label>
                            <input type="text" name="n_loop" 
                            class="form-control" value="{{$energySafety->n_loop}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label> 
                            <textarea class="form-control" name="notes" style="resize: none;">
                                {{$energySafety->notes}}
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection