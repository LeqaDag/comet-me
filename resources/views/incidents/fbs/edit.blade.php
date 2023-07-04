@extends('layouts/layoutMaster')

@section('title', 'edit fbs incident')

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
    <span class="text-muted fw-light">Edit </span> {{$fbsIncident->AllEnergyMeter->Household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('fbs-incident.update', $fbsIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" 
                                id="fbsSelectedCommuntiy" disabled>
                                @if($fbsIncident->community_id)
                                    <option value="{{$fbsIncident->community_id}}">
                                        {{$fbsIncident->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy User</label>
                            <select name="energy_user_id" class="form-control" 
                                id="energyUserSelectedFbs" disabled>
                                @if($fbsIncident->energy_user_id)
                                    <option value="{{$fbsIncident->energy_user_id}}">
                                        {{$fbsIncident->AllEnergyMeter->Household->english_name}}
                                    </option>
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
                                @if($fbsIncident->incident_id)
                                    <option value="{{$fbsIncident->incident_id}}">
                                        {{$fbsIncident->Incident->english_name}}
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
                            <label class='col-md-12 control-label'>Incident FBS Status</label>
                            <select name="incident_status_small_infrastructure_id" 
                                class="form-control">
                                @if($fbsIncident->incident_status_small_infrastructure_id)
                                    <option value="{{$fbsIncident->incident_status_small_infrastructure_id}}">
                                        {{$fbsIncident->IncidentStatusSmallInfrastructure->name}}
                                    </option>
                                    @foreach($fbsStatuses as $fbsStatus)
                                        <option value="{{$fbsStatus->id}}">
                                            {{$fbsStatus->name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($fbsStatuses as $fbsStatus)
                                        <option value="{{$fbsStatus->id}}">
                                            {{$fbsStatus->name}}
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
                            <input type="date" name="date" value="{{$fbsIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Equipments</label>
                            <textarea name="equipment" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                            {{$fbsIncident->equipment}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                            {{$fbsIncident->notes}}
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

<script>

    $(document).on('change', '#fbsSelectedCommuntiy', function () {

        community_id = $(this).val();
        $.ajax({
            url: "energy_user/get_by_community/" +  community_id,
            method: 'GET',
            success: function(data) {
                $('#energyUserSelectedFbs').prop('disabled', false);
                $('#energyUserSelectedFbs').html(data.html);
            }
        });
    });

</script>

@endsection