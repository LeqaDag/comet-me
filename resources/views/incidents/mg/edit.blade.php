@extends('layouts/layoutMaster')

@section('title', 'edit mg incident')

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
    <span class="text-muted fw-light">Edit </span> {{$mgIncident->EnergySystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('mg-incident.update', $mgIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id">
                                @if($mgIncident->community_id)
                                    <option value="{{$mgIncident->community_id}}">
                                        {{$mgIncident->Community->english_name}}
                                    </option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
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
                            <label class='col-md-12 control-label'>Energy System</label>
                            <select name="energy_system_id" class="form-control">
                                @if($mgIncident->energy_system_id)
                                    <option value="{{$mgIncident->energy_system_id}}">
                                        {{$mgIncident->EnergySystem->name}}
                                    </option>
                                    @foreach($energySystems as $energySystem)
                                    <option value="{{$energySystem->id}}">
                                        {{$energySystem->name}}
                                    </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystems as $energySystem)
                                    <option value="{{$energySystem->id}}">
                                        {{$energySystem->name}}
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
                            <label class='col-md-12 control-label'>Incident Type</label>
                            <select name="incident_id" class="form-control">
                                @if($mgIncident->incident_id)
                                    <option value="{{$mgIncident->incident_id}}">
                                        {{$mgIncident->Incident->english_name}}
                                    </option>
                                    @foreach($incidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->english_name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->english_name}}
                                        </option>
                                    @endforeach
                                @endif                                 
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Incident MG Status</label>
                            <select name="incident_status_mg_system_id" class="form-control">
                                @if($mgIncident->incident_status_mg_system_id)
                                    <option value="{{$mgIncident->incident_status_mg_system_id}}">
                                        {{$mgIncident->IncidentStatusMgSystem->name}}
                                    </option>
                                    @foreach($mgIncidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($mgIncidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->name}}
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
                            <label class='col-md-12 control-label'>Date Of Incident</label>
                            <input type="date" name="date" value="{{$mgIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                            {{$mgIncident->notes}}
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