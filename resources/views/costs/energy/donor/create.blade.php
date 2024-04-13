<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<div id="createEnergyDonorCost" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New "Energy Donor" Cost	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="energyDonorCostForm"
                    action="{{url('donor-cost')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Donor</label>
                                <select name="donor_id" class="selectpicker form-control" 
                                    data-live-search="true" id="selectedDonor">
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Year</label>
                                <select name="year" class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @php
                                        $startYear = 2023; // C
                                        $currentYear = date("Y");
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select> 
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cost from survey file with donor attributions</label>
                                <input type="text" class="form-control" name="fund"  oninput="formatInput(this)"
                                    required id="estimatedFund">
                            </fieldset>
                            <div id="fund_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'># of households</label>
                                <input type="number" class="form-control" name="household" required>
                            </fieldset>
                            <div id="household_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Commitment (funds)</label>
                                <input type="text" class="form-control" name="commitment_fund" oninput="formatInput(this)"
                                    required id="commitmentFund">
                            </fieldset>
                            <div id="commitment_fund_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Commitment (households)</label>
                                <input type="number" class="form-control" name="commitment_household" required>
                            </fieldset>
                            <div id="commitment_household_error" style="color: red;"></div>
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

    function formatInput(input) {
        // Remove commas from the input value
        input.value = input.value.replace(/,/g, '');
    }

    $(document).ready(function () {

        $('#energyDonorCostForm').on('submit', function (event) {

            var donorValue = $('#selectedDonor').val();

            if (donorValue == null) {

                $('#donor_id_error').html('Please select a donor!');
                return false;
            } else if (donorValue != null) {

                $('#donor_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#donor_id_error').empty();

            this.submit();
        });
    });
</script>

