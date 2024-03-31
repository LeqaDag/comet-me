<div id="createSubSubRegionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Sub-Sub-Region</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' id="subSubRegionForm"
                action="{{url('sub-sub-region')}}">
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
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name='region_id' class="selectpicker form-control"
                                    id="selectedRegion" data-live-search="true">
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Region</label>
                                <select name='sub_region_id' class="selectpicker form-control" 
                                    id="selectedSubRegions" data-live-search="true" 
                                    disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select> 
                            </fieldset> 
                            <div id="sub_region_id_error" style="color: red;"></div>
                        </div>
                    </div>
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
    
    $(document).on('change', '#selectedRegion', function () {
        region_id = $(this).val();
   
        $.ajax({
            url: "community/get_by_region/" + region_id,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedSubRegions');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function () {

        $('#subSubRegionForm').on('submit', function (event) {

            var regionValue = $('#selectedRegion').val();
            var subRegionValue = $('#selectedSubRegions').val();

            if (regionValue == null) {

                $('#region_id_error').html('Please select a region!'); 
                return false;
            } else if (regionValue != null){

                $('#region_id_error').empty();
            }

            if (subRegionValue == null) {

                $('#sub_region_id_error').html('Please select a sub region!'); 
                return false;
            } else if (subRegionValue != null){

                $('#sub_region_id_error').empty();
            }

            $(this).addClass('was-validated'); 
            $('#sub_region_id_error').empty();
            $('#region_id_error').empty();

            this.submit();
        });
    });
</script>
