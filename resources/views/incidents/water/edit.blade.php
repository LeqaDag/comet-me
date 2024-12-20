@extends('layouts/layoutMaster')

@section('title', 'edit water incident')

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
    @if($waterHolder->name)
        {{$waterHolder->name}} 
    @else
        @if($waterHolder->household_id)
                {{$waterHolder->Household->english_name}} 
            @else @if($waterHolder->public_structure_id)
                {{$waterHolder->PublicStructure->english_name}} 
            @endif
        @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('water-incident.update', $waterIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" 
                                id="fbsSelectedCommuntiy" disabled>
                                @if($waterIncident->community_id)
                                    <option value="{{$waterIncident->community_id}}">
                                        {{$waterIncident->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    @if($waterHolder->name) 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water System</label>
                            <select class="selectpicker form-control" 
                                    data-live-search="true" disabled>
                                    <option selected disabled>
                                        {{$waterHolder->name}} 
                                    </option>
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    @if($waterHolder->household_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water Holder</label>
                            <select name="household_id" class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option selected disabled>
                                        {{$waterHolder->Household->english_name}} 
                                    </option>
                                    <?php
                                        $households = DB::table('all_water_holders')
                                            ->join('households', 'all_water_holders.household_id', 'households.id')
                                            ->where('all_water_holders.is_archived', 0)
                                            ->where('all_water_holders.is_main', "Yes")
                                            ->where("all_water_holders.community_id", $waterHolder->community_id)
                                            ->orderBy('households.english_name', 'ASC')
                                            ->select('households.id as id', 'households.english_name')
                                            ->get();
                                    ?>
                                    @foreach($households as $household)
                                        <option value="{{$household->id}}">{{$household->english_name}}</option>
                                    @endforeach
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    @if($waterHolder->public_structure_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water Holder</label>
                            <select name="public_structure_id" class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option selected disabled>
                                        {{$waterHolder->PublicStructure->english_name}} 
                                    </option>
                                    <?php
                                        $allPublics =  DB::table('all_water_holders')
                                            ->join('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
                                            ->where('all_water_holders.is_archived', 0)
                                            ->where('all_water_holders.is_main', "Yes")
                                            ->where("all_water_holders.community_id", $waterHolder->community_id)
                                            ->orderBy('public_structures.english_name', 'ASC')
                                            ->select('public_structures.id as id', 'public_structures.english_name')
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
                                @if($waterIncident->incident_id)
                                    <option value="{{$waterIncident->incident_id}}">
                                        {{$waterIncident->Incident->english_name}}
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
                            <input type="date" name="date" value="{{$waterIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$waterIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Order Number</label>
                            <input type="number" name="order_number" class="form-control"
                                value="{{$waterIncident->order_number}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Monetary Losses</label>
                            <input type="number" name="monetary_losses" class="form-control"
                            value="{{$waterIncident->monetary_losses}}" step="0.01">
                        </fieldset>
                    </div>
                </div>

                @if($waterIncident->incident_id == 4)
                <div id="swoDiv">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Date</label>
                                <input type="date" name="order_date" class="form-control"
                                    value="{{$waterIncident->order_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Lat</label>
                                <input type="text" name="geolocation_lat" class="form-control"
                                value="{{$waterIncident->geolocation_lat}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Long</label>
                                <input type="text" name="geolocation_long" class="form-control"
                                value="{{$waterIncident->geolocation_long}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date of hearing</label>
                                <input type="date" name="hearing_date" class="form-control"
                                value="{{$waterIncident->hearing_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                <input type="text" name="building_permit_request_number" class="form-control"
                                value="{{$waterIncident->building_permit_request_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                <input type="date" name="building_permit_request_submission_date" class="form-control"
                                value="{{$waterIncident->building_permit_request_submission_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                <input type="text" name="illegal_construction_case_number" class="form-control"
                                value="{{$waterIncident->illegal_construction_case_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>District Court Case Number</label>
                                <input type="text" name="district_court_case_number" class="form-control"
                                value="{{$waterIncident->district_court_case_number}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                <input type="text" name="supreme_court_case_number" class="form-control"
                                value="{{$waterIncident->supreme_court_case_number}}">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Description of structure</label>
                                <textarea name="structure_description" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$waterIncident->structure_description}}
                                </textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Case Chronology</label>
                                <textarea name="case_chronology" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$waterIncident->case_chronology}}
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
                            {{$waterIncident->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <h5>Incident Water Statuses</h5>
                </div>
                @if(count($waterStatuses) > 0)

                    <table id="waterStatusesTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($waterStatuses as $waterStatus)
                            <tr id="waterStatusRow">
                                <td class="text-center">
                                    {{$waterStatus->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWaterIncidentStatus" id="deleteWaterIncidentStatus" 
                                        data-id="{{$waterStatus->id}}">
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
                                <label class='col-md-12 control-label'>Add More Incident Status</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_statuses[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($statuses as $status)
                                        <option value="{{$status->id}}">
                                            {{$status->name}}
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
                                <label class='col-md-12 control-label'>Add Incident Status</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_statuses[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($statuses as $status)
                                        <option value="{{$status->id}}">
                                            {{$status->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif
                <br>
                <hr>

                @if($waterIncident->incident_id != 4)
                <div class="row">
                    <h5>Equipment Damaged</h5>
                </div>
                @if(count($WaterIncidentEquipments) > 0)

                    <table id="waterIncidentEquipmentsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($WaterIncidentEquipments as $WaterIncidentEquipment)
                            <tr id="waterIncidentEquipmentRow">
                                <td class="text-center">
                                    {{$WaterIncidentEquipment->IncidentEquipment->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWaterIncidentEquipment" id="deleteWaterIncidentEquipment" 
                                        data-id="{{$WaterIncidentEquipment->id}}">
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

                <br>
                <hr>
                @endif
                <div class="row">
                    <h5>Incident Water Photos</h5>
                </div>
                @if(count($waterIncidentPhotos) > 0)

                    <table id="waterIncidentPhotosTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($waterIncidentPhotos as $waterIncidentPhoto)
                            <tr id="waterIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/water/'.$waterIncidentPhoto->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteIncidentPhoto" id="deleteIncidentPhoto" 
                                        data-id="{{$waterIncidentPhoto->id}}">
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

    // delete status
    $('#waterStatusesTable').on('click', '.deleteWaterIncidentStatus',function() {
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
                    url: "{{ route('deleteWaterIncidentStatus') }}",
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
    
    // delete photo
    $('#waterIncidentPhotosTable').on('click', '.deleteIncidentPhoto',function() {
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
                    url: "{{ route('deleteWaterIncidentPhoto') }}",
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
    $('#waterIncidentEquipmentsTable').on('click', '.deleteWaterIncidentEquipment',function() {
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
                    url: "{{ route('deleteWaterIncidentEquipment') }}",
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