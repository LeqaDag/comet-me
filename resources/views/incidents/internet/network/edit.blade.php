@extends('layouts/layoutMaster')

@section('title', 'edit internet network incident')

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
    <span class="text-muted fw-light">Edit </span> {{$networkIncident->Community->english_name}}
    <span class="text-muted fw-light">Network Incident Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('incident-network.update', 
                $networkIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($networkIncident->community_id)
                                    <option value="{{$networkIncident->community_id}}">
                                        {{$networkIncident->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Incident Type</label>
                            <select name="incident_id" class="form-control">
                                @if($networkIncident->incident_id)
                                    <option value="{{$networkIncident->incident_id}}">
                                        {{$networkIncident->Incident->english_name}}
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
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet Incident Status</label>
                            <select name="internet_incident_status_id" 
                                class="form-control">
                                @if($networkIncident->internet_incident_status_id)
                                    <option value="{{$networkIncident->internet_incident_status_id}}">
                                        {{$networkIncident->InternetIncidentStatus->name}}
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Incident</label>
                            <input type="date" name="date" value="{{$networkIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$networkIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Nature of Incident</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="50" rows="4">
                            {{$networkIncident->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Next Step</label>
                            <textarea name="next_step" class="form-control" 
                                style="resize:none" cols="50" rows="4">
                            {{$networkIncident->next_step}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Affected Households</h5>
                </div>
                @if(count($affectedHouseholds) > 0)

                    <table id="affectedHouseholdsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($affectedHouseholds as $affectedHousehold)
                            <tr id="affectedHouseholdRow">
                                <td class="text-center">
                                    {{$affectedHousehold->Household->english_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteAffectedHousehold" id="deleteAffectedHousehold" 
                                        data-id="{{$affectedHousehold->id}}">
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
                                <label class='col-md-12 control-label'>Add More Affected Households</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_affected_households[]">
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
                                <label class='col-md-12 control-label'>Add Affected Households</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_affected_households[]">
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
                    <h5>Affected Areas</h5>
                </div>
                @if(count($affectedAreas) > 0)

                    <table id="affectedAreasTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($affectedAreas as $affectedArea)
                            <tr id="affectedAreaRow">
                                <td class="text-center">
                                    {{$affectedArea->Community->english_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteAreaAffected" id="deleteAreaAffected" 
                                        data-id="{{$affectedArea->id}}">
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
                                <label class='col-md-12 control-label'>Add More Affected Areas</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_affected_areas[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
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
                                <label class='col-md-12 control-label'>Add Affected Areas</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_affected_areas[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
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
                    <h5>Equipment Damaged</h5>
                </div>
                @if(count($internetIncidentEquipments) > 0)

                    <table id="networkIncidentEquipmentsTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($internetIncidentEquipments as $internetIncidentEquipment)
                            <tr id="internetIncidentEquipmentRow">
                                <td class="text-center">
                                    {{$internetIncidentEquipment->IncidentEquipment->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteNetworkEquipment" id="deleteNetworkEquipment" 
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

                <div class="row">
                    <h5>Network Incident Photos</h5>
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
                                    <a class="btn deleteNetworkPhoto" id="deleteNetworkPhoto" 
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

    // delete household affected
    $('#affectedHouseholdsTable').on('click', '.deleteAffectedHousehold',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteAffectedHousehold') }}",
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

    // delete area affected
    $('#affectedAreasTable').on('click', '.deleteAreaAffected',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this community?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteAreaAffected') }}",
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
    $('#networkIncidentEquipmentsTable').on('click', '.deleteNetworkEquipment',function() {
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
                    url: "{{ route('deleteNetworkEquipment') }}",
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
    $('#internetIncidentPhotosTable').on('click', '.deleteNetworkPhoto',function() {
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
                    url: "{{ route('deleteNetworkPhoto') }}",
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