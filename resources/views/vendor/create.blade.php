<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>

<div id="createVendingPoint" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">
                    Create New Vending Point
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="vendingPointForm"
                    action="{{url('vendor')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" 
                                class="form-control" required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community/Town</label>
                                <select name="community_town" id="communityTownPlace" 
                                    class="selectpicker form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="community">Community</option> 
                                    <option value="town">Town</option>
                                </select>
                            </fieldset>
                            <div id="community_town_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Vending Point Place</label>
                                <select name="community_town_id" id="communityTownVendingPoint" 
                                    class="selectpicker form-control" 
                                    data-live-search="true">
                                </select>
                            </fieldset>
                            <div id="community_town_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="vendor_region_id" id="vendorRegion" 
                                    class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($vendorRegions as $vendorRegion)
                                    <option value="{{$vendorRegion->id}}">{{$vendorRegion->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="vendor_region_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Username</label>
                                <select name="vendor_user_name_id" id="vendorUsername" 
                                    class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($vendorUsers as $vendorUser)
                                    <option value="{{$vendorUser->id}}">{{$vendorUser->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="vendor_user_name_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="number" name="phone_number" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Additional Phone Number</label>
                                <input type="number" name="additional_phone_number" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Served Communities</label>
                                <select name="community_id[]" id="vendorCommunities" 
                                    class="selectpicker form-control" 
                                    data-live-search="true" multiple>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
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

    $(document).on('change', '#communityTownPlace', function () {

        communityTown = $('#communityTownPlace').val();
        
        $.ajax({
            url: "vendor/community_town/" + communityTown,
            method: 'GET',
            success: function(data) {

                var select = $('#communityTownVendingPoint');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });
    $(document).ready(function() {

        $('#vendingPointForm').on('submit', function (event) {

            var vendorRegion = $('#vendorRegion').val();
            var communityTownValue = $('#communityTownPlace').val();
            var communityTownVendingPoint = $('#communityTownVendingPoint').val();
            var vendorUsername = $('#vendorUsername').val();

            if (communityTownValue == null) {

                $('#community_town_error').html('Please select an option!'); 
                return false;
            } else if (communityTownValue != null){

                $('#community_town_error').empty();
            }

            if (communityTownVendingPoint == null) {

                $('#community_town_id_error').html('Please select an option!'); 
                return false;
            } else if (communityTownVendingPoint != null){

                $('#community_town_id_error').empty();
            }

            if (vendorRegion == null) {

                $('#vendor_region_id_error').html('Please select a region!'); 
                return false;
            } else if (vendorRegion != null){

                $('#vendor_region_id_error').empty();
            }

            

            if (vendorUsername == null) {

                $('#vendor_user_name_id_error').html('Please select a username!'); 
                return false;
            } else if (vendorUsername != null){

                $('#vendor_user_name_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#vendor_region_id_error').empty();  
            $('#vendor_user_name_id_error').empty();
            $('#community_town_error').empty();
            $('#community_town_id_error').empty();

            this.submit();
        });
    });
</script>