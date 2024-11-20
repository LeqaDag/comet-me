@extends('layouts/layoutMaster')

@section('title', 'edit internet user incident')

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
    
    @if($internetIncident->InternetUser->household_id)
            {{$internetIncident->InternetUser->Household->english_name}} 
        @else @if($internetIncident->InternetUser->public_structure_id)
            {{$internetIncident->InternetUser->PublicStructure->english_name}} 
        @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('incident-internet-user.update', $internetIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" 
                                id="fbsSelectedCommuntiy" disabled>
                                @if($internetIncident->community_id)
                                    <option value="{{$internetIncident->community_id}}">
                                        {{$internetIncident->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 

                    @if($internetHolder->household_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet Holder</label>
                            <select name="household_id" class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option selected disabled>
                                        {{$internetHolder->Household->english_name}} 
                                    </option>
                                    <?php
                                        $households = DB::table('internet_users')
                                            ->join('communities', 'internet_users.community_id', 'communities.id')
                                            ->join('households', 'internet_users.household_id', 'households.id')
                                            ->where('internet_users.community_id', $internetHolder->community_id)
                                            ->where('internet_users.is_archived', 0)
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
                    @if($internetHolder->public_structure_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet Holder</label>
                            <select name="public_structure_id" class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option selected disabled>
                                        {{$internetHolder->PublicStructure->english_name}} 
                                    </option>
                                    <?php
                                        $allPublics = DB::table('internet_users')
                                            ->join('communities', 'internet_users.community_id', 'communities.id')
                                            ->join('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
                                            ->where('internet_users.community_id', $internetHolder->community_id)
                                            ->where('internet_users.is_archived', 0)
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
                                @if($internetIncident->incident_id)
                                    <option value="{{$internetIncident->incident_id}}">
                                        {{$internetIncident->Incident->english_name}}
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
                            <label class='col-md-12 control-label'>Internet Incident Status</label>
                            <select name="internet_incident_status_id" 
                                class="form-control">
                                @if($internetIncident->internet_incident_status_id)
                                    <option value="{{$internetIncident->internet_incident_status_id}}">
                                        {{$internetIncident->InternetIncidentStatus->name}}
                                    </option>
                                    @foreach($internetIncidentStatuses as $internetIncidentStatus)
                                        <option value="{{$internetIncidentStatus->id}}">
                                            {{$internetIncidentStatus->name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIncidentStatuses as $internetIncidentStatus)
                                        <option value="{{$internetIncidentStatus->id}}">
                                            {{$internetIncidentStatus->name}}
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
                            <input type="date" name="date" value="{{$internetIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$internetIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>


                @if($internetIncident->incident_id == 4)
                <div id="swoDiv">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Number</label>
                                <input type="number" name="order_number" class="form-control"
                                    value="{{$internetIncident->order_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Date</label>
                                <input type="date" name="order_date" class="form-control"
                                    value="{{$internetIncident->order_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Lat</label>
                                <input type="text" name="geolocation_lat" class="form-control"
                                value="{{$internetIncident->geolocation_lat}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Long</label>
                                <input type="text" name="geolocation_long" class="form-control"
                                value="{{$internetIncident->geolocation_long}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date of hearing</label>
                                <input type="date" name="hearing_date" class="form-control"
                                value="{{$internetIncident->hearing_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                <input type="text" name="building_permit_request_number" class="form-control"
                                value="{{$internetIncident->building_permit_request_number}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                <input type="date" name="building_permit_request_submission_date" class="form-control"
                                value="{{$internetIncident->building_permit_request_submission_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                <input type="text" name="illegal_construction_case_number" class="form-control"
                                value="{{$internetIncident->illegal_construction_case_number}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>District Court Case Number</label>
                                <input type="text" name="district_court_case_number" class="form-control"
                                value="{{$internetIncident->district_court_case_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                <input type="text" name="supreme_court_case_number" class="form-control"
                                value="{{$internetIncident->supreme_court_case_number}}">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Description of structure</label>
                                <textarea name="structure_description" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$internetIncident->structure_description}}
                                </textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Case Chronology</label>
                                <textarea name="case_chronology" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$internetIncident->case_chronology}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Monetary Losses</label>
                            <input type="number" name="monetary_losses" class="form-control"
                            value="{{$internetIncident->monetary_losses}}" step="0.01">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                            {{$internetIncident->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                    <hr>
                @if($internetIncident->incident_id != 4)
                <div class="row">
                    <h5>Equipment Damaged</h5>
                </div>
                @if(count($internetIncidentEquipments) > 0)

                    <table id="internetIncidentEquipmentsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($internetIncidentEquipments as $internetIncidentEquipment)
                            <tr id="internetIncidentEquipmentRow">
                                <td class="text-center">
                                    {{$internetIncidentEquipment->IncidentEquipment->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteIncidentEquipment" id="deleteIncidentEquipment" 
                                        data-id="{{$internetIncidentEquipment->id}}">
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
                    <h5>Internet Holder Incident Photos</h5>
                </div>
                @if(count($internetIncidentPhotos) > 0)

                    <table id="internetIncidentPhotosTable" 
                        class="table table-striped my-2">
                        
                        <tbody>
                            @foreach($internetIncidentPhotos as $internetIncidentPhoto)
                            <tr id="internetIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/internet/'.$internetIncidentPhoto->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetUserPhoto" id="deleteInternetUserPhoto" 
                                        data-id="{{$internetIncidentPhoto->id}}">
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

    // delete photo
    $('#internetIncidentPhotosTable').on('click', '.deleteInternetUserPhoto',function() {
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
                    url: "{{ route('deleteInternetUserPhoto') }}",
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
    $('#internetIncidentEquipmentsTable').on('click', '.deleteIncidentEquipment',function() {
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
                    url: "{{ route('deleteIncidentEquipment') }}",
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