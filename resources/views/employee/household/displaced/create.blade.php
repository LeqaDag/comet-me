<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<div id="createDisplacedHousehold" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add New Displaced Families	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('displaced-household')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Old Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="communityDisplaced"
                                    name="old_community_id" required>
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
                                <label class='col-md-12 control-label'>Old Energy System</label>
                                <select name="old_energy_system_id" class="form-control" 
                                    id="oldEnergySystem" required disabled>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label class='col-md-12 control-label'>Displaced Households</label>
                            <select name="households[]" multiple id="selectedHousehold"
                                class="selectpicker form-control" data-live-search="true">
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>New Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" 
                                    name="new_community_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Area</label>
                                <select name="area" class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="A">Area A</option>
                                    <option value="B">Area B</option>
                                    <option value="C">Area C</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" 
                                    name="sub_region_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($subRegions as $subRegion)
                                    <option value="{{$subRegion->id}}">
                                        {{$subRegion->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Displacement</label>
                                <input type="date" name="displacement_date" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>System Retrieved</label>
                                <select class=" form-control"
                                    name="system_retrieved">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Destroyed">Destroyed</option>
                                </select>
                            </fieldset>
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

    $(document).on('change', '#communityDisplaced', function () {
        community_id = $(this).val();
 
        $.ajax({
            url: "displaced-household/get_system_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                console.log(data.html);
                $('#oldEnergySystem').prop('disabled', false);
                $('#oldEnergySystem').html(data.html);
            }
        });

        $.ajax({
            url: "displaced-household/get_household_by_community/" + community_id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var select = $('#selectedHousehold'); 

                select.html(response.html);
                select.selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>