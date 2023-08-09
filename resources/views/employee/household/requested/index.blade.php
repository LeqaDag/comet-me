@extends('layouts/layoutMaster')

@section('title', 'households')

@include('layouts.all')

@section('content')

<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />


<h4 class="py-3 breadcrumb-wrapper mb-4">
All<span class="text-muted fw-light"> Requested Households</span> 
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
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id != 7 ||
                Auth::guard('user')->user()->user_type_id != 11  )
                <div>
                    <p class="card-text">
                        <div>
                            <a type="button" class="btn btn-success" 
                                href="{{url('requested-household', 'create')}}" >
                                Create New Request Household	
                            </a>
                        </div>
                    </p>
                </div>
            @endif
            <table id="requestedHouseholdsTable" 
                class="table table-striped data-table-requested-households my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Phone Number</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtHouseholdId" id="txtHouseholdId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-requested-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('requested-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'phone_number', name: 'phone_number'},
                { data: 'action' }
            ]
        });
        
        // View record details
        $('#requestedHouseholdsTable').on('click', '.detailsHouseholdButton',function() {
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
    
        // View record details
        $('#requestedHouseholdsTable').on('click', '.updateHousehold',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, '_self'); 
                }
            });
        });

        // Delete record
        $('#requestedHouseholdsTable').on('click', '.deleteHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteHousehold') }}",
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
                                    $('#requestedHouseholdsTable').DataTable().draw();
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