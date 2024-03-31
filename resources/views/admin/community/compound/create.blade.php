
<div id="createCompoundHouseholds" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Compound Households</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div> 
            <form method="POST" enctype='multipart/form-data' id="communityCompoundHouseholdForm">
                @csrf
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityCompoundChanges" 
                                    class="selectpicker form-control" required
                                    data-live-search="true" data-parsley-required="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Compound</label>
                                <select name="compound_id" id="selectedCompound" required
                                    class="form-control" data-parsley-required="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="compound_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id[]" id="selectedHouseholdCompound" 
                                    class="form-control selectpicker" 
                                    data-live-search="true" multiple>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="household_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System Type</label>
                                <select name="energy_system_type_id" id="selectedEnergyType"
                                    class="selectpicker form-control" data-live-search="true"
                                    required data-parsley-required="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystemTypes as $energySystemType)
                                    <option value="{{$energySystemType->id}}">
                                        {{$energySystemType->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_system_type_id_error" style="color: red;"></div>
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
     
    $(document).on('change', '#communityCompoundChanges', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "community-compound/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedCompound').prop('disabled', false);
                $('#selectedCompound').html(data.htmlCompounds);

                var select = $('#selectedHouseholdCompound'); 

                select.html(data.htmlHouseholds);
                select.selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $(document).ready(function () {
 
        $('#communityCompoundHouseholdForm').on('submit', function (event) {

            var communityMain = $('#communityCompoundChanges').val();
            var compoundValue = $('#selectedCompound').val();
            var energyValue = $('#selectedEnergyType').val();
            var householdValue = $('#selectedHouseholdCompound').val();

            if (communityMain == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityMain != null) {

                $('#community_id_error').empty();
            }
            
            if (compoundValue == null) {

                $('#compound_id_error').html('Please select a compound!'); 
                return false;
            } else if (compoundValue != null) {

                $('#compound_id_error').empty();
            }

            if (!householdValue || householdValue.length === 0) {

                $('#household_id_error').html('Please select at least one household!');
                return false;
            } else {

                $('#household_id_error').empty();
            }

            if (energyValue == null) {

                $('#energy_system_type_id_error').html('Please select an energy type!'); 
                return false;
            } else if (energyValue != null) {

                $('#energy_system_type_id_error').empty();
            }

            $('#selectedHousehold').prop('disabled', false);
            
            $(this).addClass('was-validated');  
            $('#community_id_error').empty(); 
            $('#compound_id_error').empty();
            $('#energy_system_type_id_error').empty();
            $('#household_id_error').empty();

            $.ajax({ 
                url: "community-compound",
                method: 'POST',
                success: function(data) {
                
                }
            });
        });
    });
</script>