<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<div id="createCommunityDonor" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Compound/Community Donor
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="communityDonorForm"
                    action="{{url('community-donor')}}">
                    @csrf
                    <div class="row">
                        <div id="community_compound_error" style="color: red;"></div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" class="selectpicker form-control" 
                                    data-live-search="true" id="selectedCommunity">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Compound</label>
                                <select name="compound_id" class="selectpicker form-control" 
                                    data-live-search="true" id="selectedCompound" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select> 
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Service Type</label>
                                <select name="service_id" class="selectpicker form-control"
                                    data-live-search="true" id="serviceSelected">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($services as $service)
                                    <option value="{{$service->id}}">
                                        {{$service->service_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="service_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Donor</label>
                                <select name="donor_id[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple id="donorsSelected">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($donors as $donor)
                                    <option value="{{$donor->id}}">
                                        {{$donor->donor_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="donor_id_error" style="color: red;"></div>
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
                
                var select = $('#selectedCompound'); 
                select.prop('disabled', false);
                select.html(data.htmlCompounds);
                select.selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $(document).ready(function () {

        $('#communityDonorForm').on('submit', function (event) {

            var communityValue = $('#selectedCommunity').val();
            var compoundValue = $('#selectedCompound').val();
            var serviceValue = $('#serviceSelected').val();
            var donorValue = $('#donorsSelected').val();

            if (communityValue == null && compoundValue == null) {

                $('#community_compound_error').html('Please select a compound or community!');
                return false;
            } else {

                $('#community_compound_error').empty();
            }

            if (serviceValue == null) {

                $('#service_id_error').html('Please select a service!'); 
                return false;
            } else if (serviceValue != null){

                $('#service_id_error').empty();
            }

            if (!donorValue || donorValue.length === 0) {

                $('#donor_id_error').html('Please select at least one donor!');
                return false;
            } else {

                $('#donor_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#service_id_error').empty(); 
            $('#community_compound_error').empty();
            $('#donor_id_error').empty();

            this.submit();
        });
    });
</script>

