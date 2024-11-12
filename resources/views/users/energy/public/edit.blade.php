@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit Energy Public')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label {
        margin-top: 20px;
    } 
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$energyPublic->PublicStructure->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-public.update', $energyPublic->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')

                <div class="row">
                    <h5>General Details</h5>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" data-live-search="true">
                                <option selected disabled>{{$energyPublic->Community->english_name}}</option>
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
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" data-live-search="true"
                            class="selectpicker form-control" >
                            @if($energyPublic->energy_system_cycle_id)
                                <option disabled selected>
                                    {{$energyPublic->EnergySystemCycle->name}}
                                </option>
                                @foreach($energyCycles as $energyCycle)
                                <option value="{{$energyCycle->id}}">
                                    {{$energyCycle->name}}
                                </option>
                                @endforeach
                            @else
                            <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                <option value="{{$energyCycle->id}}">
                                    {{$energyCycle->name}}
                                </option>
                                @endforeach
                            @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Number</label>
                            <input type="text" class="form-control" name="meter_number"
                                value="{{$energyPublic->meter_number}}"> 
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Daily limit</label> 
                            <input type="text" class="form-control" name="daily_limit"
                                value="{{$energyPublic->daily_limit}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation date</label>
                            <input type="date" class="form-control" name="installation_date" 
                            value="{{$energyPublic->installation_date}}"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Meter Active</label> 
                            <select name='meter_active' class="selectpicker form-control">
                                <option selected disabled>
                                    {{$energyPublic->meter_active}}
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
                            <select name='meter_case_id' name="meter_case_id " class="selectpicker form-control">
                                <option disabled selected>
                                    {{$energyPublic->MeterCase->meter_case_name_english}}
                                </option>
                                @foreach($meterCases as $meterCase)
                                    <option value="{{$meterCase->id}}">
                                        {{$meterCase->meter_case_name_english}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 text-info">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Updated date (only for meter case)</label>
                            <input type="date" class="form-control text-info" name="last_update_date" 
                            > 
                        </fieldset>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Vendor Name</label> 
                            <select name='vendor_username_id' class="selectpicker form-control">
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New/Old Community</label> 
                            <select name='installation_type_id' class="selectpicker form-control">
                                <option selected disabled>
                                    {{$energyPublic->InstallationType->type}}
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
                            <select name='energy_system_id' class="selectpicker form-control">
                                <option selected disabled>
                                    {{$energyPublic->EnergySystem->name}}
                                </option>
                                @foreach($energySystems as $energySystem)
                                    <option value="{{$energySystem->id}}">{{$energySystem->name}}</option>
                                @endforeach
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Ground Connected</label> 
                            <select name='ground_connected'  data-live-search="true"
                            class="selectpicker form-control">
                                <option selected disabled>
                                    {{$energyPublic->ground_connected}}
                                </option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> 
                        </fieldset> 
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label> 
                            <textarea class="form-control" name="notes" style="resize: none;">
                                {{$energyPublic->notes}}
                            </textarea>
                        </fieldset> 
                    </div>
                </div><hr>
 
                <div class="row">
                    <h5>CI & PH</h5>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>CI</label> 
                            <select name='electricity_collection_box_id' data-live-search="true"
                            class="selectpicker form-control">
                                @if($allEnergyMeterPhase)
                                <option selected disabled>
                                    {{$allEnergyMeterPhase->ElectricityCollectionBox->name}}
                                </option>
                                @foreach($electricityCollectionBoxes as $electricityCollectionBox)
                                    <option value="{{$electricityCollectionBox->id}}">{{$electricityCollectionBox->name}}</option>
                                @endforeach
                                @else
                                
                                <option selected disabled>Choose one...</option>
                                @foreach($electricityCollectionBoxes as $electricityCollectionBox)
                                    <option value="{{$electricityCollectionBox->id}}">{{$electricityCollectionBox->name}}</option>
                                @endforeach
                                @endif
                            </select> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PH (L)</label> 
                            <select name='electricity_phase_id' data-live-search="true"
                            class="selectpicker form-control">
                                @if($allEnergyMeterPhase)
                                <option selected disabled>
                                    {{$allEnergyMeterPhase->ElectricityPhase->name}}
                                </option>
                                @foreach($electricityPhases as $electricityPhasE)
                                    <option value="{{$electricityPhasE->id}}">{{$electricityPhasE->name}}</option>
                                @endforeach
                                @else
                                
                                <option selected disabled>Choose one...</option>
                                @foreach($electricityPhases as $electricityPhasE)
                                    <option value="{{$electricityPhasE->id}}">{{$electricityPhasE->name}}</option>
                                @endforeach
                                @endif
                            </select> 
                        </fieldset> 
                    </div>
                </div> 

                <hr>
                <div class="row">
                    <h5>Donors</h5>
                </div>
                @if(count($energyDonors) > 0)
                    <table id="energyDonorsPublicTable" 
                        class="table table-striped data-public-energy-donors my-2">  
                        <tbody>
                            @foreach($energyDonors as $energyDonor)
                            <tr id="energyDonorRow">
                                <td class="text-center">
                                    {{$energyDonor->Donor->donor_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergyPublicDonor" id="deleteEnergyPublicDonor" data-id="{{$energyDonor->id}}">
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

<script type="text/javascript">
    $(function () {

        // delete energy donor
        $('#energyDonorsPublicTable').on('click', '.deleteEnergyPublicDonor',function() {
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
                        url: "{{ route('deleteEnergyPublicDonor') }}",
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