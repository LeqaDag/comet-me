<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>  

<div id="createCommunityCamera" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Installed Camera
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('camera')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" id="communityCamera"
                                    data-live-search="true" name="community_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Installation</label>
                                <input type="date" name="date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Responsible?</label>
                                <select name="household_id" id="householdResponsible"
                                    class="selectpicker form-control" data-live-search="true">
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>Cameras</h6>
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
                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>NVR Cameras</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveNvr">
                                <tr>
                                    <th>NVR Model</th>
                                    <th># of NVR</th>
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
                                        <button type="button" name="add" id="addNvrForCommunityButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                  
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload photos</label>
                            <input type="file" name="photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {

    $(document).on('change', '#communityCamera', function () {

        $('#householdResponsible').empty();
        community_id = $(this).val();
        $.ajax({
            url: "progress-household/household/get_by_community/" +  community_id,
            method: 'GET',
            success: function(data) {
                var select = $('#householdResponsible'); 

                select.html(data.html);
                select.selectpicker('refresh');
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
            '<input type="text"' +
            'name="addMoreInputFieldsNvrNumber[][subject]" placeholder="# of NVR"' +
            'class="target_point form-control" data-id="'+ i +'" /></td><td><button type="button"' +
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