@extends('layouts/layoutMaster')

@section('title', 'edit installed camera')

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
    <span class="text-muted fw-light">Edit </span> 
    @if($cameraCommunity->community_id)

        {{$cameraCommunity->Community->english_name}} 
        @else @if($cameraCommunity->repository_id)

        {{$cameraCommunity->Repository->name}} 
        @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('camera.update', $cameraCommunity->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row" style="margin-top:12px">
                    <h5>General</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        @if($cameraCommunity->community_id)
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <input type="text" value="{{$cameraCommunity->Community->english_name}}"
                                    class="form-control" disabled>
                            </fieldset>
                        @else @if($cameraCommunity->repository_id)

                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Repository</label>
                                <input type="text" value="{{$cameraCommunity->Repository->name}}"
                                    class="form-control" disabled>
                            </fieldset>
                        @endif
                        @endif
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Installation</label>
                            <input type="date" name="date" class="form-control"
                            value="{{$cameraCommunity->date}}">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Comet-Me Internally</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" name="comet_internal">
                                @if($cameraCommunity->comet_internal == 0)
                                    <option value="0" disabled selected>No</option>
                                    <option value="1">Yes</option>
                                @else @if($cameraCommunity->comet_internal == 1)
                                    <option value="1" disabled selected>Yes</option>
                                    <option value="0">No</option>

                                @else 
                                    <option selected disabled>Choose one...</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                @endif
                                @endif
                                
                            </select>
                        </fieldset>
                    </div> 
                    @if($cameraCommunity->community_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Responsible</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" name="household_id">
                                @if($cameraCommunity->household_id)
                                    <option value="{{$cameraCommunity->Household->id}}" disabled selected>
                                        {{$cameraCommunity->Household->english_name}}
                                    </option>
                                    @foreach($households as $household)
                                    <option value="{{$household->id}}">
                                        {{$household->english_name}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($households as $household)
                                <option value="{{$household->id}}">
                                    {{$household->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    @endif
                </div>
                <hr> <br>

                <div class="row" style="margin-top:12px">
                    <h5>Cameras</h5>
                </div>
                @if(count($communityCameraTypes) > 0)

                    <table id="communityCameraTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($communityCameraTypes as $communityCameraType)
                            <tr id="communityCameraTypesRow">
                                <td class="text-center">
                                    {{$communityCameraType->Camera->model}}
                                </td>
                                <td class="text-center">
                                    {{$communityCameraType->number}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteCommunityCamera" 
                                        id="deleteCommunityCamera"
                                        data-id="{{$communityCameraType->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Cameras</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveCamera">
                                <tr>
                                    <th>Camera Model</th>
                                    <th># of Camera</th>
                                    <th>Options</th>
                                </tr>
                                <tr> 
                                    <td>
                                        <select class="selectpicker form-control" 
                                            data-live-search="true" name="camera_id[]" required>
                                            <option disabled selected>Choose one...</option>
                                            @foreach($cameras as $camera)
                                            <option value="{{$camera->id}}">
                                                {{$camera->model}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsCameraNumber[0][subject]" 
                                        placeholder="# of Camera" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addCameraForCommunityButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else 

                    <div class="row">
                        <h6>Add New Cameras</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveCamera">
                                <tr>
                                    <th>Camera Model</th>
                                    <th># of Camera</th>
                                    <th>Options</th>
                                </tr>
                                <tr> 
                                    <td>
                                        <select class="selectpicker form-control" 
                                            data-live-search="true" name="camera_id[]" required>
                                            <option disabled selected>Choose one...</option>
                                            @foreach($cameras as $camera)
                                            <option value="{{$camera->id}}">
                                                {{$camera->model}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsCameraNumber[0][subject]" 
                                        placeholder="# of Camera" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addCameraForCommunityButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr>
                <div class="row" style="margin-top:12px">
                    <h5>NVR Cameras</h5>
                </div>
                @if(count($communityNvrTypes) > 0)

                    <table id="communityNvrCameraTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($communityNvrTypes as $communityNvrType)
                            <tr id="communityNvrTypesRow">
                                <td class="text-center">
                                    {{$communityNvrType->NvrCamera->model}} / 
                                    <span class="text-info">{{$communityNvrType->number}} </span>
                                </td>
                                <td class="text-center" id="existingIpAddressNvr_{{$communityNvrType->id}}">
                                    <input type="text" class="ipAddressNvr form-control" data-id="{{$communityNvrType->id}}"
                                        value="{{$communityNvrType->ip_address}}" placeholder="Enter IP Address">
                                </td>
                                <td class="text-center">
                                    <a class="btn updateCommunityNvrCamera" 
                                        id="updateCommunityNvrCamera"
                                        data-id="{{$communityNvrType->id}}">
                                        <i class="fa fa-pen text-info"></i>
                                    </a>
                                    <a class="btn deleteCommunityNvrCamera" 
                                        id="deleteCommunityNvrCamera"
                                        data-id="{{$communityNvrType->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More NVR Cameras</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveNvr">
                                <tr>
                                    <th>NVR Model</th>
                                    <th># of NVR</th>
                                    <th>IP Address</th>
                                    <th>Options</th>
                                </tr>
                                <tr> 
                                    <td>
                                        <select class="selectpicker form-control"
                                            data-live-search="true" name="nvr_id[]" required>
                                            <option disabled selected>Choose one...</option>
                                            @foreach($nvrCameras as $nvrCamera)
                                            <option value="{{$nvrCamera->id}}">
                                                {{$nvrCamera->model}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsNvrNumber[0][subject]" 
                                        placeholder="# of NVR" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsNvrIpAddress[0][subject]" 
                                        placeholder="IP Address" class="target_point form-control" 
                                        data-id="0" />
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addNvrForCommunityButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else 

                    <div class="row">
                        <h6>Add New NVR Cameras</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveNvr">
                                <tr>
                                    <th>NVR Model</th>
                                    <th># of NVR</th>
                                    <th>IP Address</th>
                                    <th>Options</th>
                                </tr>
                                <tr> 
                                    <td>
                                        <select class="selectpicker form-control"
                                            data-live-search="true" name="nvr_id[]" required>
                                            <option disabled selected>Choose one...</option>
                                            @foreach($nvrCameras as $nvrCamera)
                                            <option value="{{$nvrCamera->id}}">
                                                {{$nvrCamera->model}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsNvrNumber[0][subject]" 
                                        placeholder="# of NVR" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsNvrIpAddress[0][subject]" 
                                        placeholder="IP Address" class="target_point form-control" 
                                        data-id="0" />
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addNvrForCommunityButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$cameraCommunity->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h5>Photos</h5>
                </div>
                @if(count($cameraCommunityPhotos) > 0)

                    <table id="cameraCommunityPhotoTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($cameraCommunityPhotos as $CameraCommunityPhoto)
                            <tr id="CameraCommunityPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/cameras/community/'.$CameraCommunityPhoto->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteCommunityCameraPhoto" id="deleteCommunityCameraPhoto" 
                                        data-id="{{$CameraCommunityPhoto->id}}">
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

                <hr>
                <div class="row">
                    <h5>Donors</h5>
                </div>
                @if(count($cameraDonors) > 0)
                    <table id="cameraDonorsTable" class="table table-striped data-table-camera-donors my-2">
                        <tbody>
                            @foreach($cameraDonors as $cameraDonor)
                            <tr id="cameraDonorRow">
                                <td class="text-center">
                                    {{$cameraDonor->Donor->donor_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteCameraDonor" id="deleteCameraDonor" data-id="{{$cameraDonor->id}}">
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
                                <label class='col-md-12 control-label'>Add more donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">
                                            {{$donor->donor_name}}
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
                                <label class='col-md-12 control-label'>Add Donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">{{$donor->donor_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
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

$(document).ready(function() {

    // delete camera donor
    $('#cameraDonorsTable').on('click', '.deleteCameraDonor',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this donor?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCameraDonor') }}",
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

    // delete Camera-Community Photo
    $('#cameraCommunityPhotoTable').on('click', '.deleteCommunityCameraPhoto',function() {
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
                    url: "{{ route('deleteCommunityCameraPhoto') }}",
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

    // delete installed camera type
    $('#communityCameraTable').on('click', '.deleteCommunityCamera',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this installed camera?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityCamera') }}",
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

    // delete installed camera type
    $('#communityNvrCameraTable').on('click', '.deleteCommunityNvrCamera',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this installed NVR?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityNvrCamera') }}",
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

    // add ip address for nvr
    $('.ipAddressNvr').change(function () {

        var id = $(this).data('id');
        var ip_address = $(this).val();

        $.ajax({
            type: 'POST',
            url: "/camera/update_ip",
            data: {
                id: id,
                ip_address: ip_address,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'IP Address updated successfully!',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!'
                }).then((result) => {
                    
                });
            },
            error: function (error) {
                // Handle error, if needed
                console.error(error);
            }
        });
    });

    // Add More Cameras
    var j = 0;
    const cameras = {!! json_encode($cameras) !!};
    $("#addCameraForCommunityButton").click(function () {
        ++j;

        let options = '<option disabled selected>Choose one...</option>';
        for (const cameraId in cameras) {
            const camera = cameras[cameraId];
            options += '<option value="' + camera.id + '">' + camera.model + '</option>';
        }                  

        $("#dynamicAddRemoveCamera").append('<tr><td><select class="selectpicker form-control"' + 
            'data-live-search="true" name="camera_id[]">' + options +
            '</select></td><td>' +
            '<input type="text"' +
            'name="addMoreInputFieldsCameraNumber[][subject]" placeholder="# of Camera"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">Delete</button></td>' +
            '</tr>'
        );

        $(".selectpicker").selectpicker('refresh');
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });


    // Add More NVRs
    var i = 0;
    const nvrCameras = {!! json_encode($nvrCameras) !!};
    $("#addNvrForCommunityButton").click(function () {
        ++i;

        let options = '<option disabled selected>Choose one...</option>';
        for (const nvrId in nvrCameras) {
            const nvr = nvrCameras[nvrId];
            options += '<option value="' + nvr.id + '">' + nvr.model + '</option>';
        }                  

        $("#dynamicAddRemoveNvr").append('<tr><td><select class="selectpicker form-control"' + 
            'data-live-search="true" name="nvr_id[]">' + options +
            '</select></td><td>' +
            '<input required type="text"' +
            'name="addMoreInputFieldsNvrNumber[][subject]" placeholder="# of NVR"' +
            'class="target_point form-control" data-id="'+ i +'" /></td><td><input type="text"'+ 
            'name="addMoreInputFieldsNvrIpAddress[][subject]" placeholder="IP Address"'+
            'data-id="'+ i +'" class="target_point form-control"></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-nvr">Delete</button></td>' +
            '</tr>'
        );

        $(".selectpicker").selectpicker('refresh');
    });
    $(document).on('click', '.remove-input-field-nvr', function () {
        $(this).parents('tr').remove();
    });

});

</script>

@endsection