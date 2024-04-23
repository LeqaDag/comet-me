@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit community')

@include('layouts.all')

<style>
    label, input{ 
    display: block;
}
label {
    margin-top: 20px;
}
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$community->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('community.update', $community->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" value="{{$community->english_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                value="{{$community->arabic_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="region_id" id="selectedRegion" 
                                    class="selectpicker form-control" data-live-search="true"required>
                                    <option disabled selected>{{$community->Region->english_name}}</option>
                                    @foreach($regions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Region</label>
                                <select name="sub_region_id" id="selectedSubRegions" 
                                class="selectpicker form-control" data-live-search="true" required>
                                    <option disabled selected>{{$community->SubRegion->english_name}}</option>
                                    @foreach($subRegions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community Status</label>
                                <select name="community_status_id" data-live-search="true"
                                class="selectpicker form-control" >
                                    <option disabled selected>
                                        {{$community->CommunityStatus->name}}
                                    </option>
                                    @foreach($communityStatuses as $communityStatus)
                                    <option value="{{$communityStatus->id}}">
                                        {{$communityStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year</label>
                                <select name="energy_system_cycle_id" data-live-search="true"
                                class="selectpicker form-control" >
                                @if($community->energy_system_cycle_id)
                                    <option disabled selected>
                                        {{$community->EnergySystemCycle->name}}
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
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cellular Reception?</label>
                                <select name="reception" class="form-control">
                                    <option disabled selected>{{$community->reception}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Households</label>
                                <input type="text" name="number_of_household" 
                                value="{{$community->number_of_household}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of People</label>
                                <input type="text" name="number_of_people" 
                                value="{{$community->number_of_people}}" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Products type</label>
                                <select name="product_type_id" class="form-control">
                                    @if($community->ProductType)
                                        <option disabled selected>
                                            {{$community->ProductType->name}}
                                        </option>
                                    @else
                                        <option disabled selected>Choose one...</option>
                                    @endif
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset> 
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Fallah</label>
                                <select name="is_fallah" class="form-control">
                                    <option disabled selected>{{$community->is_fallah}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset> 
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Bedouin</label>
                                <select name="is_bedouin" class="form-control">
                                    <option disabled selected>{{$community->is_bedouin}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Demolition orders/demolitions </label>
                                <input type="text" name="demolition" 
                                value="{{$community->demolition}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Lawyer</label>
                                <input type="text" name="lawyer" class="form-control"
                                    value="{{$community->lawyer}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Land Status</label>
                                <input type="text" name="land_status" 
                                value="{{$community->land_status}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Latitude</label>
                                <input type="text" name="latitude" 
                                value="{{$community->latitude}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Longitude</label>
                                <input type="text" name="longitude" 
                                value="{{$community->longitude}}" class="form-control">
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3">
                                   {{$community->notes}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>
                   
                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>Second Name for community</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in English</label>
                                
                                @if($secondName)

                                    <input name="second_name_english" type="text" 
                                        value="{{$secondName->english_name}}" class="form-control">
                                @else

                                    <input name="second_name_english" type="text" class="form-control">
                                @endif
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in Arabic</label>
                                @if($secondName)

                                    <input name="second_name_arabic" type="text" 
                                        value="{{$secondName->arabic_name}}" class="form-control">
                                @else

                                    <input name="second_name_arabic" type="text" class="form-control">
                                @endif
                            </fieldset>
                        </div>
                    </div> 
 
                    <hr style="margin-top:30px">
                    <div class="row">
                        <h6>Recommended Energy Systems</h6>
                    </div>
                    @if(count($recommendedEnergySystems) > 0)

                        <table id="recommendedEnergySystemsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($recommendedEnergySystems as $recommendedEnergySystem)
                                <tr id="recommendedEnergySystemsRow">
                                    <td class="text-center">
                                        {{$recommendedEnergySystem->EnergySystemType->name}}
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="numbers" value="{{$recommendedEnergySystem->numbers}}"
                                            placeholder="How many systems?" class="target_point form-control" 
                                            data-id="{{$recommendedEnergySystem->id}}"
                                            data-name="{{$recommendedEnergySystem->community_id}}"
                                            id="recommended_numbers"/>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteRecommendedEnergySystems" 
                                            id="deleteRecommendedEnergySystems"
                                            data-id="{{$recommendedEnergySystem->id}}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Energy System Type</th>
                                        <th>Numbers</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr> 
                                        <td>
                                            <select class="form-control"  name="recommended_systems">
                                                <option selected disabled>Choose one...</option>
                                                @foreach($energySystemTypes as $energySystemType)
                                                    <option value="{{$energySystemType->id}}">
                                                        {{$energySystemType->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="How many systems?" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Add More Recommended Energy Systems</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="recommended_systems[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($energySystemTypes as $energySystemType)
                                            <option value="{{$energySystemType->id}}">
                                                {{$energySystemType->name}}
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
                                    <label class='col-md-12 control-label'>Add Recommended Energy Systems</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="new_recommended_systems[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($energySystemTypes as $energySystemType)
                                            <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif


                    <hr style="margin-top:30px">
                    <div class="row">
                        <h6>Compounds</h6>
                    </div>
                    @if(count($compounds) > 0)

                        <table id="communityCompoundTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compounds as $compound)
                                <tr id="compoundsRow">
                                    <td class="text-center">
                                        {{$compound->english_name}}
                                    </td>
                                    <td class="text-center">
                                        {{$compound->arabic_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteCommunityCompound" 
                                            id="deleteCommunityCompound"
                                            data-id="{{$compound->id}}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <span>Add More Compounds</span>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Compound Name</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="Enter English Copmound Name" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                    @else
                        <div class="row">
                            <h6>Add New Compounds</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Compound Name</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="Enter English Copmound Name" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif


                    <hr>
                    <div class="row">
                        <h5>System Details</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Service</label>
                                <select name="energy_service" class="form-control">
                                    <option disabled selected>{{$community->energy_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Service Year</label>
                                <input type="text" name="energy_service_beginning_year" 
                                value="{{$community->energy_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Service</label>
                                <select name="water_service" class="form-control">
                                    <option disabled selected>{{$community->water_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Service Year</label>
                                <input type="text" name="water_service_beginning_year" 
                                value="{{$community->water_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Service</label>
                                <select name="internet_service" class="form-control">
                                    <option disabled selected>{{$community->internet_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Service Year</label>
                                <input type="text" name="internet_service_beginning_year" 
                                value="{{$community->internet_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                            <fieldset class="form-group">
                                <input type="text" name="description" class="form-control"
                                    id="percentageInputQuestion1" 
                                    style="visiblity:hidden; display:none">
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

    $('#recommended_numbers').on('change', function(){

        var number = $(this).val();
        var community_id = $(this).data("name");
        alert(community_id);
        $.ajax({
            url: "/recommended/numbers",
            method: 'POST',
            data: {
                number : number,
                community_id : community_id
            },
            success: function(data) {
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
        });
    });

    // delete community compound
    $('#communityCompoundTable').on('click', '.deleteCommunityCompound',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Compound?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityCompound') }}",
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

    var j = 0;
    $("#addCompoundNameButton").click(function () {
        ++j;
        $("#dynamicAddRemoveCompoundName").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsCompoundName[][subject]" placeholder="Enter Another one"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });
</script>
@endsection