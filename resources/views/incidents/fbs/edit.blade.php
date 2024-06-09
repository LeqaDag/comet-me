@extends('layouts/layoutMaster')

@section('title', 'edit energy user incident')

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
    <span class="text-muted fw-light">Edit </span> 
    @if($energyMeter->household_id)
            {{$energyMeter->Household->english_name}} 
        @else @if($energyMeter->public_structure_id)
            {{$energyMeter->PublicStructure->english_name}} 
        @endif
    @endif
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
                    @if($energyMeter->household_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Holder</label>
                            <select name="household_id" class="selectpicker form-control" 
                                id="energyUserSelectedFbs" data-live-search="true">
                                    <option selected disabled value="{{$fbsIncident->energy_user_id}}">
                                        {{$energyMeter->Household->english_name}} 
                                    </option>
                                    <?php
                                        $allUsers = DB::table('all_energy_meters')
                                            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                                            ->where("households.community_id", $energyMeter->community_id)
                                            ->select('households.id', 'households.english_name')
                                            ->orderBy('households.english_name', 'ASC') 
                                            ->get();
                                    ?>
                                    @foreach($allUsers as $allUser)
                                        <option value="{{$allUser->id}}">{{$allUser->english_name}}</option>
                                    @endforeach
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    @if($energyMeter->public_structure_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Holder</label>
                            <select name="public_structure_id" class="selectpicker form-control" 
                                id="energyUserSelectedFbs" data-live-search="true">
                                    <option selected disabled value="{{$fbsIncident->energy_user_id}}">
                                        {{$energyMeter->PublicStructure->english_name}} 
                                    </option>
                                    <?php
                                        $allPublics = DB::table('all_energy_meters')
                                            ->join('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
                                            ->where("public_structures.community_id", $energyMeter->community_id)
                                            ->select('public_structures.id', 'public_structures.english_name')
                                            ->orderBy('public_structures.english_name', 'ASC') 
                                            ->get();
                                    ?>
                                    @foreach($allPublics as $allPublic)
                                        <option value="{{$allPublic->id}}">{{$allPublic->english_name}}</option>
                                    @endforeach
                            </select>
                        </fieldset>
                    </div>
                    @endif
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
                            <label class='col-md-12 control-label'>Date Of Incident</label>
                            <input type="date" name="date" value="{{$fbsIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$fbsIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Losses Energy (ILS)</label>
                            <input type="number" name="losses_energy" class="form-control"
                            value="{{$fbsIncident->losses_energy}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Losses Water (ILS)</label>
                            <input type="number" name="losses_water" class="form-control"
                            value="{{$fbsIncident->losses_water}}">
                        </fieldset>
                    </div>
                </div>

                @if($fbsIncident->incident_id == 4)
                <div id="swoDiv">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Number</label>
                                <input type="number" name="order_number" class="form-control"
                                    value="{{$fbsIncident->order_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Date</label>
                                <input type="date" name="order_date" class="form-control"
                                    value="{{$fbsIncident->order_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Lat</label>
                                <input type="text" name="geolocation_lat" class="form-control"
                                value="{{$fbsIncident->geolocation_lat}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Long</label>
                                <input type="text" name="geolocation_long" class="form-control"
                                value="{{$fbsIncident->geolocation_long}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date of hearing</label>
                                <input type="date" name="hearing_date" class="form-control"
                                value="{{$fbsIncident->hearing_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Description of structure</label>
                                <textarea name="structure_description" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$fbsIncident->structure_description}}
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
                            {{$fbsIncident->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h5>Incident Energy User Statuses</h5>
                </div>
                @if(count($fbsStatuses) > 0) 

                    <table id="fbsStatusesTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($fbsStatuses as $fbsStatus)
                            <tr id="fbsStatusRow">
                                <td class="text-center">
                                    {{$fbsStatus->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteIncidentStatus" id="deleteIncidentStatus" 
                                        data-id="{{$fbsStatus->id}}">
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
                                <label class='col-md-12 control-label'>Add More Energy User Status</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_statuses[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($fbsIncidentStatues as $fbsStatus)
                                        <option value="{{$fbsStatus->id}}">
                                            {{$fbsStatus->name}}
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
                                <label class='col-md-12 control-label'>Add Energy User Status</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_statuses[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($fbsIncidentStatues as $fbsStatus)
                                        <option value="{{$fbsStatus->id}}">
                                            {{$fbsStatus->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif
                <br>
                <hr>

                @if($fbsIncident->incident_id != 4)
                <div class="row">
                    <h5>Equipment Damaged</h5>
                </div>
                @if(count($fbsIncidentEquipments) > 0)

                    <table id="fbsIncidentEquipmentsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($fbsIncidentEquipments as $fbsIncidentEquipment)
                            <tr id="fbsIncidentEquipmentRow">
                                <td class="text-center">
                                    {{$fbsIncidentEquipment->IncidentEquipment->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteIncidentEquipment" id="deleteIncidentEquipment" 
                                        data-id="{{$fbsIncidentEquipment->id}}">
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
                @endif
                <br>
                <hr>

                <div class="row">
                    <h5>Incident Energy User Photos</h5>
                </div>
                @if(count($fbsIncidentPhotos) > 0)

                    <table id="fbsIncidentPhotosTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($fbsIncidentPhotos as $fbsIncidentPhoto)
                            <tr id="fbsIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/energy/'.$fbsIncidentPhoto->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteIncidentPhoto" id="deleteIncidentPhoto" 
                                        data-id="{{$fbsIncidentPhoto->id}}">
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

    // delete FBS Photo
    $('#fbsIncidentPhotosTable').on('click', '.deleteIncidentPhoto',function() {
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
                    url: "{{ route('deleteIncidentPhoto') }}",
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

    // delete FBS Status
    $('#fbsStatusesTable').on('click', '.deleteIncidentStatus',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this status?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteIncidentStatus') }}",
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
    $('#fbsIncidentEquipmentsTable').on('click', '.deleteIncidentEquipment',function() {
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
                    url: "/delete-fbs-equipment/" + id,
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