<div id="createSubRegionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Sub-Region</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' id="subRegionForm"
                action="{{url('sub-region')}}">
            @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" class="form-control" name="english_name" 
                                    placeholder="Enter English Name" required> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label> 
                                <input type="text" class="form-control" name="arabic_name" 
                                    placeholder="Enter Arabic Name" required> 
                            </fieldset> 
                        </div>
                    </div>
                    <br>
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label' >Region</label>
                        <select name='region_id' class="selectpicker form-control" 
                            data-live-search="true" id="regionSelected" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($regions as $region)
                            <option value="{{$region->id}}">
                                {{$region->english_name}}
                            </option>
                            @endforeach
                        </select> 
                    </fieldset> 
                    <div id="region_id_error" style="color: red;"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {

        $('#subRegionForm').on('submit', function (event) {

            var regionValue = $('#regionSelected').val();

            if (regionValue == null) {

                $('#region_id_error').html('Please select a region!'); 
                return false;
            } else if (regionValue != null){

                $('#region_id_error').empty();
            }
            $(this).addClass('was-validated'); 
            $('#region_id_error').empty();

            this.submit();
        });
    });
</script>