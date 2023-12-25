@extends('layouts/layoutMaster')
@include('layouts.all')
@section('title', 'create energy request')
<style>
    label, input{
    display: block;
}
.dropdown-toggle{
        height: 40px;
        
    }
label {
    margin-top: 20px;
}
</style>
@section('vendor-style')


@endsection


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Request Energy System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
        <form method="POST" action="{{url('energy-request')}}" enctype="multipart/form-data" >
            @csrf
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Community</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="selectedRequestCommunity"
                            name="community_id" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                            <option value="{{$community->id}}">
                                {{$community->english_name}}
                            </option> 
                            @endforeach
                        </select>
                        @if ($errors->has('community_id'))
                            <span class="error">{{ $errors->first('community_id') }}</span>
                        @endif
                    </fieldset>
                </div> 
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Household</label>
                        <select name="household_id" class="selectpicker form-control" 
                            id="selectedRequestHousehold" data-live-search="true" disabled
                            multiple>
                            <option disabled selected>Choose one...</option>
                        </select>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Status of request</label>
                        <select name="energy_request_status_id" 
                            class="selectpicker form-control" data-live-search="true"
                            id="actionSystemSelect">
                            <option disabled selected>Choose one...</option>
                            @foreach($requestStatuses as $requestStatus) 
                                <option value="{{$requestStatus->id}}">
                                    {{$requestStatus->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('energy_request_status_id'))
                            <span class="error">{{ $errors->first('energy_request_status_id') }}</span>
                        @endif
                    </fieldset>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Request Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Recommended Energy System Type</label>
                        <select name="recommendede_energy_system_id" 
                            class="selectpicker form-control" data-live-search="true" >
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                            <option value="{{$energySystemType->id}}">
                                {{$energySystemType->name}}
                            </option>
                            @endforeach
                        </select>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Referred by</label>
                        <textarea name="referred_by" class="form-control" 
                            style="resize:none" cols="20" rows="3"></textarea>
                    </fieldset>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Notes</label>
                        <textarea name="notes" class="form-control" 
                            style="resize:none" cols="20" rows="3"></textarea>
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
   
    $(document).on('change', '#selectedRequestCommunity', function () {

        community_id = $(this).val();
        $.ajax({
            url: "energy-request/get_by_community/" +  community_id,
            method: 'GET',  
            success: function(data) {
                $('#selectedRequestHousehold').prop('disabled', false);

                var select = $('#selectedRequestHousehold'); 

                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 
    });

</script>

@endsection