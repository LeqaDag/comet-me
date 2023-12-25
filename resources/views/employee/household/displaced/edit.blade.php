@extends('layouts/layoutMaster')

@section('title', 'edit displaced household')

@include('layouts.all')

<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$displacedHousehold->Household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('displaced-household.update', $displacedHousehold->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
           

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Old Community</label>
                            <input type="text" value="{{$displacedHousehold->OldCommunity->english_name}}"
                                class="form-control" disabled>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Old Energy System</label>
                            @if($displacedHousehold->old_energy_system_id)
                            <input type="text" class="form-control" disabled
                                value="{{$displacedHousehold->OldEnergySystem->name}}">
                            @else 
                            <input type="text" class="form-control" disabled
                                value="" name="old_energy_system_id">
                            @endif
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New Community</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" 
                                name="new_community_id" required>
                                @if($displacedHousehold->NewCommunity)
                                    <option value="{{$displacedHousehold->NewCommunity->id}}" disabled selected>
                                        {{$displacedHousehold->NewCommunity->english_name}}
                                    </option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New Energy System</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" 
                                name="new_energy_system_id" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($energySystems as $energySystem)
                                <option value="{{$energySystem->id}}">
                                    {{$energySystem->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Area</label>
                            <select name="area" class="form-control" required>
                            @if($displacedHousehold->area)
                                <option value="{{$displacedHousehold->area}}" disabled selected>
                                    Area {{$displacedHousehold->area}}
                                </option>
                                <option value="A">Area A</option>
                                <option value="B">Area B</option>
                                <option value="C">Area C</option>
                            @else
                                <option disabled selected>Choose one...</option>
                                <option value="A">Area A</option>
                                <option value="B">Area B</option>
                                <option value="C">Area C</option>
                            @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Region</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" 
                                name="sub_region_id" required>
                            @if($displacedHousehold->SubRegion)
                                <option value="{{$displacedHousehold->SubRegion->id}}" disabled selected>
                                    {{$displacedHousehold->SubRegion->english_name}}
                                </option>
                                @foreach($subRegions as $subRegion)
                                <option value="{{$subRegion->id}}">
                                    {{$subRegion->english_name}}
                                </option>
                                @endforeach
                            @else
                                <option selected disabled>Choose one...</option>
                                @foreach($subRegions as $subRegion)
                                <option value="{{$subRegion->id}}">
                                    {{$subRegion->english_name}}
                                </option>
                                @endforeach
                            @endif
                            </select>
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Displacement</label>
                            <input type="date" name="displacement_date" class="form-control"
                            value="{{$displacedHousehold->displacement_date}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>System Retrieved</label>
                            <select class=" form-control" name="system_retrieved">
                            @if($displacedHousehold->system_retrieved)
                                <option value="{{$displacedHousehold->system_retrieved}}" disabled selected>
                                    {{$displacedHousehold->system_retrieved}}
                                </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Destroyed">Destroyed</option>
                            @else
                                <option disabled selected>Choose one...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Destroyed">Destroyed</option>
                            @endif
                            </select>
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$displacedHousehold->notes}}
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