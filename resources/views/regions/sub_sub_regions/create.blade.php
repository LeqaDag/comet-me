<div id="createSubSubRegionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Sub-Sub-Region</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' action="{{url('sub-sub-region')}}">
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
                                    placeholder="Enter Arabic Name"> 
                            </fieldset> 
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name='region_id' class="form-control" id="selectedRegion">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($regions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select> 
                            </fieldset> 
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Region</label>
                                <select name='sub_region_id' class="form-control" 
                                    id="selectedSubRegions" disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select> 
                            </fieldset> 
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
                $('#selectedSubRegions').prop('disabled', false);
                $('#selectedSubRegions').html(data.html);
            }
        });
    });
</script>
