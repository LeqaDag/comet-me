@extends('layouts/layoutMaster')

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

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
                            name="community_id[]" required>
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
                        <select name="household_id" class="form-control" 
                            id="selectedRequestHousehold" disabled>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
   
    $(document).on('change', '#selectedUserCommunity', function () {

        community_id = $(this).val();
        $.ajax({
            url: "household/get_by_community/" +  community_id,
            method: 'GET',  
            success: function(data) {
                $('#selectedUserHousehold').prop('disabled', false);
                $('#selectedUserHousehold').html(data.html);
            }
        }); 

        energy_type_id= $("#selectedEnergySystemType").val();

        changeEnergySystemType(energy_type_id, community_id);
    });

</script>

@endsection