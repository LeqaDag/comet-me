

<div id="createPublicStructure" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Public Structure
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="publicStructureForm" 
                    action="{{url('public-structure')}}">
                    @csrf
                    <div class="row"> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="selectedCommunity" 
                                    class="selectpicker form-control" data-live-search="true"  
                                    data-parsley-required="true" required>
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
                                <select name="compound_id" id="compoundPublicStructure" 
                                    class="selectpicker form-control" data-live-search="true"> 
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" id="publicEnglishName"
                                    class="form-control" required>
                            </fieldset>
                            <div id="english_name_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                    required id="publicArabicName">
                            </fieldset>
                            <div id="arabic_name_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Out of comet?</label>
                                <select name="out_of_comet" id="outOfComet"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </fieldset>
                            <div id="out_of_comet_error" style="color: red;"></div>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System Type</label>
                                <select name="energy_system_type_id"
                                    class="selectpicker form-control" data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystemTypes as $energySystemType)
                                    <option value="{{$energySystemType->id}}">
                                        {{$energySystemType->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year</label>
                                <select name="energy_system_cycle_id" data-live-search="true"
                                class="selectpicker form-control">
                                <option disabled selected>Choose one...</option>
                                    @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 1</label>
                                <select name="public_structure_category_id1"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required id="categoryValue">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="public_structure_category_id1_error" style="color: red;"></div>
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 2</label>
                                <select name="public_structure_category_id2"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Category 3</label>
                                <select name="public_structure_category_id3"
                                    class="selectpicker form-control" data-live-search="true"  
                                        required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publicCategories as $publicCategory)
                                    <option value="{{$publicCategory->id}}">
                                        {{$publicCategory->name}}
                                    </option>
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
                                    style="resize:none" cols="20" rows="2"></textarea>
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
   
    $(document).on('change', '#selectedCommunity', function () {
        community_id = $(this).val();
   
        $.ajax({ 
            url: "community-compound/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#compoundPublicStructure').prop('disabled', false);

                var select = $('#compoundPublicStructure'); 

                select.html(data.htmlCompounds);
                select.selectpicker('refresh');
            }
        }); 
    });

    $(document).ready(function () {

        $('#publicStructureForm').on('submit', function (event) {

            var communityValue = $('#selectedCommunity').val();
            var englishValue = $('#publicEnglishName').val();
            var arabicValue = $('#publicArabicName').val();
            var outOfCometValue = $('#outOfComet').val();
            var categoryValue = $('#categoryValue').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (englishValue == null) {

                $('#english_name_error').html('Please type an English name!');
                return false;
            } else if (englishValue != null){

                $('#english_name_error').empty();
            }

            if (arabicValue == null) {

                $('#arabic_name_error').html('Please type an Arabic name!');
                return false;
            } else if (arabicValue != null){

                $('#arabic_name_error').empty();
            }

            if (outOfCometValue == null) {

                $('#out_of_comet_error').html('Please select an option!'); 
                return false;
            } else if (outOfCometValue != null) {

                $('#out_of_comet_error').empty();
            }

            if (categoryValue == null) {

                $('#public_structure_category_id1_error').html('Please select category!'); 
                return false;
            } else if (categoryValue != null) {

                $('#public_structure_category_id1_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#out_of_comet_error').empty(); 
            $('#community_id_error').empty(); 
            $('#english_name_error').empty(); 
            $('#arabic_name_error').empty(); 
            $('#energy_system_cycle_id_error').empty(); 
            $('#energy_system_type_id_error').empty();
            $('#public_structure_category_id1_error').empty();

            this.submit();
        });
    });

</script>