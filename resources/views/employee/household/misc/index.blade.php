@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'MISC')


@include('layouts.all')

@section('content')


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Confirmed MISC
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('employee.household.details')

<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Community</label>
                        <select name="community_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByCommunity">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Region</label>
                        <select name="region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($regions as $region)
                                <option value="{{$region->id}}">{{$region->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="miscHouseholdsTable" 
                class="table table-striped data-table-misc-households my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var table;
    function DataTableContent() {

        table = $('.data-table-misc-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('misc-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByCommunity').val();
                    d.second_filter = $('#filterByRegion').val();
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'region_name', name: 'region_name'},
                {data: 'action' }
            ]
        });
    }

    $(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var filterByCommunity = urlParams.get('filterByCommunity');

        if (filterByCommunity) {

            $('#filterByCommunity').val(filterByCommunity);
        }

        DataTableContent();

        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-misc-households')) {
                $('.data-table-misc-households').DataTable().destroy();
            }
            DataTableContent();
        });

        // View record details
        $('#miscHouseholdsTable').on('click', '.updateHousehold', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'misc-household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, '_self'); 
                }
            });
        });

        // View record details
        $('#miscHouseholdsTable').on('click', '.detailsHouseholdButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'household/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#householdModalTitle').html(" ");
                    $('#englishNameHousehold').html(" ");
                    $('#arabicNameHousehold').html(" ");
                    $('#communityHousehold').html(" ");
                    $('#professionHousehold').html(" ");
                    $('#numberOfMaleHousehold').html(" ");
                    $('#numberOfFemaleHousehold').html(" ");
                    $('#numberOfChildrenHousehold').html(" ");
                    $('#numberOfAdultsHousehold').html(" ");
                    $('#phoneNumberHousehold').html(" ");
                    $('#energyServiceHousehold').html(" ");
                    $('#energyMeterHousehold').html(" ");
                    $('#waterServiceHousehold').html(" ");
                    $('#energyStatusHousehold').html(" ");

                    $('#householdModalTitle').html(response['household'].english_name);
                    $('#englishNameHousehold').html(response['household'].english_name);
                    $('#arabicNameHousehold').html(response['household'].arabic_name);
                    $('#communityHousehold').html(response['community'].english_name);
                    if(response['profession']) $('#professionHousehold').html(response['profession'].profession_name);
                    $('#numberOfMaleHousehold').html(response['household'].number_of_male);
                    $('#numberOfFemaleHousehold').html(response['household'].number_of_female);
                    $('#numberOfChildrenHousehold').html(response['household'].number_of_children);
                    $('#numberOfAdultsHousehold').html(response['household'].number_of_adults);
                    $('#phoneNumberHousehold').html(response['household'].phone_number);
                    $('#energyServiceHousehold').html(response['household'].energy_service);
                    $('#energyMeterHousehold').html(response['household'].energy_meter);
                    $('#waterServiceHousehold').html(response['household'].water_service);
                    $('#energyStatusHousehold').html(response['status'].status);
                    
                    $('#numberOfCistern').html(" ");
                    $('#numberOfCistern').html(response['cistern'].number_of_cisterns);
                    $('#volumeCistern').html(" ");
                    $('#volumeCistern').html(response['cistern'].volume_of_cisterns);
                    $('#depthCistern').html(" ");
                    $('#depthCistern').html(response['cistern'].depth_of_cisterns);
                    $('#sharedCistern').html(" ");
                    $('#sharedCistern').html(response['cistern'].shared_cisterns);
                    $('#distanceCistern').html(" ");
                    $('#distanceCistern').html(response['cistern'].distance_from_house);
                    $('#herdSize').html(" ");
                    $('#herdSize').html(response['household'].size_of_herd);
                    $('#numberOfStructures').html(" ");
                    $('#numberOfStructures').html(response['structure'].number_of_structures);
                    $('#numberOfkitchens').html(" ");
                    $('#numberOfkitchens').html(response['structure'].number_of_kitchens);
                    $('#numberOfShelters').html(" ");
                    $('#numberOfShelters').html(response['structure'].number_of_animal_shelters);
                    
                    if(response['communityHousehold']) {

                        $('#izbih').html(" ");
                        $('#izbih').html(response['communityHousehold'].is_there_izbih);
                        $('#houseInTown').html(" ");
                        $('#houseInTown').html(response['communityHousehold'].is_there_house_in_town);
                        $('#howLong').html(" ");
                        $('#howLong').html(response['communityHousehold'].how_long);
                        $('#lengthOfStay').html(" ");
                        $('#lengthOfStay').html(response['communityHousehold'].length_of_stay);
                    }
                    
                    $('#energySourceHousehold').html(" ");
                    $('#energySourceHousehold').html(response['household'].electricity_source);
                    $('#energySourceSharedHousehold').html(" ");
                    $('#energySourceSharedHousehold').html(response['household'].electricity_source_shared);
                    $('#notesHousehold').html(" ");
                    $('#notesHousehold').html(response['household'].notes);
                }
            });
        });

        // Move record
        $('#miscHouseholdsTable').on('click', '.moveMISCHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to start working?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('moveMISCHousehold') }}",
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
                                    $('#miscHouseholdsTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
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