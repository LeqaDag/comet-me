<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>
<div id="createHouseholdMeter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Meter User
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('household-meter')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy User</label>
                                <select name="energy_user_id" id="selectedEnergyUser" 
                                    class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($households as $household)
                                    <option value="{{$household->id}}">
                                        {{$household->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id" id="selectedAllHousehold" 
                                class="form-control" disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select>
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

    $(document).on('change', '#selectedEnergyUser', function () {
        user_id = $(this).val();
   
        $.ajax({
            url: "household-meter/get_households/" + user_id,
            method: 'GET',
            success: function(data) {

                $('#selectedAllHousehold').prop('disabled', false);
                $('#selectedAllHousehold').html(data.html);
            }
        });
    });

    $(document).on('change', '#selectedEnergySystemType', function () {
        energy_type_id = $(this).val();
   
        $.ajax({
            url: "energy-user/get_by_energy_type/" + energy_type_id,
            method: 'GET',
            success: function(data) {
                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedEnergySystem').html(data.html);
            }
        });
    });

</script>