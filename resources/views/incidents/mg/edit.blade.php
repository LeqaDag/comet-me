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
                            <select name="incident_id" class="form-control" id="incidentMgType">
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$mgIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                @if($mgIncident->incident_id == 4)
                <div id="swoDiv">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Number</label>
                                <input type="number" name="order_number" class="form-control"
                                    value="{{$mgIncident->order_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Date</label>
                                <input type="date" name="order_date" class="form-control"
                                    value="{{$mgIncident->order_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Lat</label>
                                <input type="text" name="geolocation_lat" class="form-control"
                                value="{{$mgIncident->geolocation_lat}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Long</label>
                                <input type="text" name="geolocation_long" class="form-control"
                                value="{{$mgIncident->geolocation_long}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date of hearing</label>
                                <input type="date" name="hearing_date" class="form-control"
                                value="{{$mgIncident->hearing_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Description of structure</label>
                                <textarea name="structure_description" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$mgIncident->structure_description}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                @endif
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
                    
                <hr>

                <div class="row">
                    <h5>Equipment Damaged</h5>
                </div>
                @if(count($mgIncidentEquipments) > 0)

                    <table id="mgIncidentEquipmentsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($mgIncidentEquipments as $mgIncidentEquipment)
                            <tr id="mgIncidentEquipmentRow">
                                <td class="text-center">
                                    {{$mgIncidentEquipment->IncidentEquipment->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteMgIncidentEquipment" id="deleteMgIncidentEquipment" 
                                        data-id="{{$mgIncidentEquipment->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add More Equipment Damaged</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_equipment[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($incidentEquipments as $incidentEquipment)
                                        <option value="{{$incidentEquipment->id}}">
                                            {{$incidentEquipment->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Equipment Damaged</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_equipment[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($incidentEquipments as $incidentEquipment)
                                        <option value="{{$incidentEquipment->id}}">
                                            {{$incidentEquipment->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

                <hr>

                <div class="row">
                    <h5>Households Affected</h5>
                </div>
                @if(count($mgAffectedHouseholds) > 0)

                    <table id="mgAffectedHouseholdsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($mgAffectedHouseholds as $mgAffectedHousehold)
                            <tr id="mgAffectedHouseholdRow">
                                <td class="text-center">
                                    {{$mgAffectedHousehold->english_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deletemgAffectedHousehold" id="deletemgAffectedHousehold" 
                                        data-id="{{$mgAffectedHousehold->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add More Households Affected</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_household[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($households as $household)
                                        <option value="{{$household->id}}">
                                            {{$household->english_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Households Affected</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_household[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($households as $household)
                                        <option value="{{$household->id}}">
                                            {{$household->english_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

                <br>
                <hr>

                <div class="row">
                    <h5>Incident MG Photos</h5>
                </div>
                @if(count($mgIncidentPhotos) > 0)

                    <table id="mgIncidentPhotosTable" 
                        class="table table-striped data-table-mg-equipments my-2">
                        
                        <tbody>
                            @foreach($mgIncidentPhotos as $mgIncidentPhoto)
                            <tr id="mgIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/mg/'.$mgIncidentPhoto->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteMgIncidentPhoto" id="deleteMgIncidentPhoto" 
                                        data-id="{{$mgIncidentPhoto->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload More photos</label>
                            <input type="file" name="more_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                @else 
                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload new photos</label>
                            <input type="file" name="new_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
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

<script>

    $(document).on('change', '#incidentMgType', function () {

        incident_type_id = $(this).val();

        if(incident_type_id == 4)  {

            $("#swoDiv").css("display", "block");
            $("#swoDiv").css("visiblity", "visible");
        } else {

            $("#swoDiv").css("display", "none");
            $("#swoDiv").css("visiblity", "none");
        }
    });

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

    // delete photo
    $('#mgIncidentPhotosTable').on('click', '.deleteMgIncidentPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteMgIncidentPhoto') }}",
                    type: 'get',
                    data: {id: id},
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // delete damaged equipment
    $('#mgIncidentEquipmentsTable').on('click', '.deleteMgIncidentEquipment',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this equipment?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteMgIncidentEquipment') }}",
                    type: 'get',
                    data: {id: id},
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // delete affected household
    $('#mgAffectedHouseholdsTable').on('click', '.deletemgAffectedHousehold',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household affected?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletemgAffectedHousehold') }}",
                    type: 'get',
                    data: {id: id},
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });
</script>

@endsection