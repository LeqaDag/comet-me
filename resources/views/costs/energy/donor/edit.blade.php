@extends('layouts/layoutMaster')

@section('title', 'edit donor cost')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    } 
</style> 

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$energyDonorCost->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('donor-cost.update', $energyDonorCost->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')

                <div class="row">
                    @if($energyDonorCost->Donor)
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Donor</label>
                            <input type="text" class="form-control" disabled
                            value="{{$energyDonorCost->Donor->donor_name}}">
                        </fieldset>
                    </div>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cost from survey file with donor attributions</label>
                            <input type="text" class="form-control" name="fund"  oninput="formatInput(this)"
                                value="{{$energyDonorCost->fund}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of households</label>
                            <input type="number" class="form-control" name="household" 
                            value="{{$energyDonorCost->household}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Commitment (funds)</label>
                            <input type="text" class="form-control" name="commitment_fund" oninput="formatInput(this)"
                            value="{{$energyDonorCost->commitment_fund}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Commitment (households)</label>
                            <input type="number" class="form-control" name="commitment_household"
                            value="{{$energyDonorCost->commitment_household}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    function formatInput(input) {
        // Remove commas from the input value
        input.value = input.value.replace(/,/g, '');
    }

</script>
@endsection