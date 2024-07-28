@extends('layouts/layoutMaster')

@section('title', 'edit water log')

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
    <span class="text-muted fw-light">Edit </span> {{$waterSystemLog->WaterSystem->name}}
    <span class="text-muted fw-light">Log Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('water-log.update', $waterSystemLog->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Test Date</label>
                            <input type="date" name="test_date" class="form-control" 
                            value="{{$waterSystemLog->test_date}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Leakage</label>
                            <select name="leakage" class="selectpicker form-control" data-live-search="true"
                                id="leakage">
                                <option disabled selected>
                                    {{$waterSystemLog->leakage}}
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
                            <label class='col-md-12 control-label'>Reachability</label>
                            <input type="text" name="reachability" class="form-control" 
                                value="{{$waterSystemLog->reachability}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Free Chlorine "PPM"</label>
                            <input type="text" name="free_chlorine" value="{{$waterSystemLog->free_chlorine}}"
                            class="form-control">
                        </fieldset>
                    </div> 
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PH</label>
                            <input type="text" name="ph" value="{{$waterSystemLog->ph}}"
                                class="form-control">
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electrical Conductivity - EC - (MC)</label>
                            <input type="text" name="ec" value="{{$waterSystemLog->ec}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Reading (m3)</label>
                            <input type="text" name="meter_reading" class="form-control"
                            value="{{$waterSystemLog->meter_reading}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Daily Avg Cluster Consumption (m3/cluster)</label>
                            <input type="number" name="daily_avg_cluster_consumption" class="form-control"
                                value="{{$waterSystemLog->daily_avg_cluster_consumption}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Daily Avg Capita Consumption (L/day)</label>
                            <input type="number" name="daily_avg_capita_consumption" class="form-control"
                            value="{{$waterSystemLog->daily_avg_capita_consumption}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$waterSystemLog->notes}}
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