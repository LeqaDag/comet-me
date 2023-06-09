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
    <span class="text-muted fw-light">Edit </span>
        @if($waterResult->household_id) 
            {{$waterResult->Household->english_name}}
        @else 
            {{$waterResult->PublicStructure->english_name}}
        @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('quality-result.update', $waterResult->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Visiting Date</label>
                            <input type="date" name="date" value="{{$waterResult->date}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electrical Conductivity "EC"</label>
                            <input type="text" name="ec" value="{{$waterResult->ec}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Free Chlorine "FCI"</label>
                            <input type="text" name="fci" value="{{$waterResult->fci}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PH</label>
                            <input type="text" name="ph" value="{{$waterResult->ph}}"
                                class="form-control">
                        </fieldset>
                    </div>  
                    
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Biological Contamination "CFU"</label>
                            <input type="text" name="cfu" value="{{$waterResult->cfu}}"
                            class="form-control">
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