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
    <span class="text-muted fw-light">Edit </span> {{$energySystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-system.update', $energySystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                @if($energySystemPvs)
                    <div class="row">
                        <h6>Solar Panles "PVs"</h6>
                    </div>
                    <div class="row">
                    @foreach($energySystemPvs as $energySystemPv)
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>PV</label>
                                <select name="pv_type_id[]" class="form-control">
                                    <option value="{{$energySystemPv->EnergyPv->id}}"
                                        disabled selected>
                                        {{$energySystemPv->EnergyPv->pv_model}}
                                    </option>
                                    
                                    @foreach($solarPanles as $solarPanle)
                                        <option value="{{$solarPanle->id}}">
                                            {{$solarPanle->pv_model}}
                                        </option>
                                    @endforeach
                                    
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'># of pvs</label> 
                                <input type="number" class="form-control" name="pv_units[]"
                                    value="{{$energySystemPv->pv_units}}"> 
                            </fieldset> 
                        </div>
                    @endforeach
                    </div>
                    <hr>
                @endif

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