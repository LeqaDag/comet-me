@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit household')

@include('layouts.all')

<style>
    label, input {

        display: block;
    }

    .dropdown-toggle {

        height: 40px;
    }

    label {

        margin-top: 20px;
    }

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$energyUser->Household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-meter.update', $energyUser->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <h5>General Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Number</label>
                            <input type="text" class="form-control" name="meter_number"
                                value="{{$energyUser->meter_number}}"> 
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Daily limit</label> 
                            <input type="text" class="form-control" name="daily_limit"
                                value="{{$energyUser->daily_limit}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation date</label>
                            <input type="date" class="form-control" name="installation_date" 
                            value="{{$energyUser->installation_date}}"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Active</label> 
                            <select name='meter_active' class="form-control">
                                <option selected disabled>
                                    {{$energyUser->meter_active}}
                                </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' for="region_id">Meter Case</label>
                            <select name='meter_case_id' name="meter_case_id " class="form-control">
                                <option disabled selected>
                                    {{$energyUser->MeterCase->meter_case_name_english}}
                                </option>
                                @foreach($meterCases as $meterCase)
                                    <option value="{{$meterCase->id}}">
                                        {{$meterCase->meter_case_name_english}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Vendor Name</label> 
                            <select name='vendor_username_id' class="form-control">
                                <option selected disabled>
                                    @if($vendor)
                                    {{$vendor->name}}
                                    @else
                                    Choose one...
                                    @endif
                                </option>
                                @foreach($communityVendors as $vendor)
                                    <option value="{{$vendor->vendor_username_id}}">
                                        {{$vendor->name}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New/Old Community</label> 
                            <select name='misc' class="form-control">
                                <option value="">
                                    {{$energyUser->InstallationType->type}}
                                </option>
                                @foreach($installationTypes as $installationType)
                                    <option value="{{$installationType->id}}">
                                        {{$installationType->type}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System</label> 
                            <select name='energy_system_id' class="form-control">
                                <option selected disabled>
                                    {{$energyUser->EnergySystem->name}}
                                </option>
                                @foreach($energySystems as $energySystem)
                                    <option value="{{$energySystem->id}}">{{$energySystem->name}}</option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label> 
                            <textarea class="form-control" name="notes" style="resize: none;">
                                {{$energyUser->notes}}
                            </textarea>
                        </fieldset> 
                    </div>
                </div> <hr>

                <div class="row">
                    <h5>Donors</h5>
                </div>
                @if(count($energyDonors) > 0)

                    <table id="energyDonorsTable" class="table table-striped data-table-energy-donors my-2">
                        
                        <tbody>
                            @foreach($energyDonors as $energyDonor)
                            <tr id="energyDonorRow">
                                <td class="text-center">
                                    {{$energyDonor->Donor->donor_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergyDonor" id="deleteEnergyDonor" data-id="{{$energyDonor->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add more donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">
                                            {{$donor->donor_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">{{$donor->donor_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $(function () {

        // delete energy donor
        $('#energyDonorsTable').on('click', '.deleteEnergyDonor',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this donor?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergyDonor') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $ele.fadeOut(1000, function () {
                                        $ele.remove();
                                    });
                                });
                            } 
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });
    });
</script>

@endsection