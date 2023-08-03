<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}

.headingLabel {
    font-size:18px;
    font-weight: bold;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<div id="createRefrigeratorHolder" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Refrigerator Holder	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('refrigerator-user')}}">
                    @csrf
                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" name="community_id" data-live-search="true" 
                                    id="communityChanges" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->arabic_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('community_id'))
                                    <span class="error">{{ $errors->first('community_id') }}</span>
                                @endif
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household/Public Structure</label>
                                <select name="is_household" id="isHousehold" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Household</option>
                                    <option value="no">Public Structure</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator Holder</label>
                                <select name="household_id" id="selectedRefrigeratorHolder" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator Type</label>
                                <select name="refrigerator_type_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="No frost">No frost</option>
                                    <option value="De frost">De frost</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Refrigerator</label>
                                <input type="number" name="number_of_fridge" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date</label>
                                <input type="date" name="date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Year</label>
                                <input type="number" name="year" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Is Paid?</label>
                                <select name="is_paid" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Free">Free</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Payment</label>
                                <input type="number" name="payment" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Receipt Number</label>
                                <input type="text" name="receive_number" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                    style="resize:none" cols="20" rows="1">
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();
   
        $('#isHousehold').prop('disabled', false);
        
        $(document).on('change', '#isHousehold', function () {
            is_household = $(this).val();
            
            if(is_household == "yes") {

                $("#selectedRefrigeratorHolder").attr('name', 'household_id');
                $.ajax({
                    url: "household/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {
                        $('#selectedRefrigeratorHolder').prop('disabled', false);
                        $('#selectedRefrigeratorHolder').html(data.html);
                    }
                });

            } else {

                $("#selectedRefrigeratorHolder").attr('name', 'public_structure_id');
                $.ajax({
                    url: "energy-public/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {
                        $('#selectedRefrigeratorHolder').prop('disabled', false);
                        $('#selectedRefrigeratorHolder').html(data.html);
                    }
                });
            }
        });
    });

</script>