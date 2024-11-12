<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
    
    .dropdown-toggle {
        height: 40px;
    }
</style>

<div id="createRequestedCamera" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add New Requested camera
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="requestedCameraForm">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" data-live-search="true" 
                                    name="community_id" required data-parsley-required="true"
                                    id="community_id">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select> 
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
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
                                <label class='col-md-12 control-label'>Request Status</label>
                                <select name="camera_request_status_id" id="camera_request_status_id"
                                    class="selectpicker form-control" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($requestStatuses as $requestStatus)
                                    <option value="{{$requestStatus->id}}">{{$requestStatus->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="camera_request_status_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Who took the request?</label>
                                <select name="user_id" class="selectpicker form-control" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Reffered By</label>
                                <textarea name="referred_by" class="form-control" style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(document).on('change', '#community_id', function () {

            community_id = $(this).val();

            $.ajax({
                url: "/camera-request/has_camera/"+ community_id,
                method: 'GET',  
                success: function(data) {

                    $('#community_id_error').html(" ");
            
                    if(data.cameraDetails == null)  $('#community_id_error').html("Doesn't have installed camera!");
                    else {

                        $('#community_id_error').html("To see his cameras details click : <a target='_blank' href='/camera/"+ data.cameraDetails["id"] +"'>here </a>");
                    }
                }
            }); 
        });

        $('#requestedCameraForm').on('submit', function (event) {
       
            var communityValue = $('#community_id').val();
            var statusValue = $('#camera_request_status_id').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } 
            if (statusValue == null) {

                $('#camera_request_status_id_error').html('Please select a status!'); 
                return false;
            }
                
            $(this).addClass('was-validated');  
            $('#community_id_error').empty();
            $('#camera_request_status_id_error').empty();
        });
    }); 
</script>
