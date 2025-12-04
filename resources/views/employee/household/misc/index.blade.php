@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'MISC')


@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyRequestExport" aria-expanded="false" 
        aria-controls="collapseEnergyRequestExport"> 
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p> 

<div class="collapse multi-collapse container mb-4" id="collapseEnergyRequestExport">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export Requested Systems Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearRequestedFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('misc-household.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($communities as $community)
                                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Status</label>
                                        <select name="status" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            <option value="served">Served</option>
                                            <option value="service_requested">Service requested</option>
                                        </select>  
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>System Type if Shared</label>
                                        <select name="energy_system_type_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($energySystemTypes as $energySystemType)
                                                <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Request Date</label>
                                        <input type="date" name="request_date" class="form-control"
                                            id="filterByRequestedDateExport">
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Download Excel</label>
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div> 
    </div> 
</div>


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
@include('public.show')

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
                        <th class="text-center">Referred By</th>
                        <th class="text-center">Notes</th>
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
                {data: 'referred_by', name: 'referred_by'},
                {data: 'confirmation_notes', name: 'confirmation_notes'},
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

                    if(response['cistern']) {

                        $('#numberOfCistern').html(response['cistern'].number_of_cisterns);
                        $('#volumeCistern').html(" ");
                        $('#volumeCistern').html(response['cistern'].volume_of_cisterns);
                        $('#depthCistern').html(" ");
                        $('#depthCistern').html(response['cistern'].depth_of_cisterns);
                        $('#sharedCistern').html(" ");
                        $('#sharedCistern').html(response['cistern'].shared_cisterns);
                        $('#distanceCistern').html(" ");
                        $('#distanceCistern').html(response['cistern'].distance_from_house);
                    }


                    $('#herdSize').html(" ");
                    $('#herdSize').html(response['household'].size_of_herd);

                    if(response['structure']) {

                        $('#numberOfStructures').html(" ");
                        $('#numberOfStructures').html(response['structure'].number_of_structures);
                        $('#numberOfCaves').html(" ");
                        $('#numberOfCaves').html(response['structure'].number_of_cave);
                        $('#numberOfkitchens').html(" ");
                        $('#numberOfkitchens').html(response['structure'].number_of_kitchens);
                        $('#numberOfShelters').html(" ");
                        $('#numberOfShelters').html(response['structure'].number_of_animal_shelters);
                    }
                    
                    if(response['energyCycleYear'] != []) {

                        $('#energyCycleYear').html(" ");
                        $('#energyCycleYear').html(response['energyCycleYear'].name);
                    }

                    
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

        // View public details
        $('#miscHouseholdsTable').on('click', '.detailsPublicButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'public-structure/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {

                    $('#publicStructureModalTitle').html('');
                    $('#publicStructureModalTitle').html(response['publicStructure'].english_name);
                    $('#englishNamePublic').html('');
                    $('#englishNamePublic').html(response['publicStructure'].english_name);
                    $('#arabicNamePublic').html('');
                    $('#arabicNamePublic').html(response['publicStructure'].arabic_name);
                    $('#communityName').html('');
                    $('#communityName').html(response['community'].english_name);
                    $('#publicNotes').html('');
                    $('#publicNotes').html(response['publicStructure'].notes);
                    $('#publicStatus').html('');
                    $('#publicStatus').html(response['status'].status);

                    $("#kindergartenDetails").css("visibility", "none");
                    $("#kindergartenDetails").css('display', 'none');

                    $("#schoolDetails").css("visibility", "none");
                    $("#schoolDetails").css('display', 'none');
                    $('#compoundName').html('');
                    if(response['compound']) {
                        
                        $('#compoundName').html('');
                        $('#compoundName').html(response['compound'].english_name);
                    }
                    if(response['schoolPublic']) {

                        $("#schoolDetails").css("visibility", "visible");
                        $("#schoolDetails").css('display', 'block');
                        $('#totalSchoolStudents').html('');
                        $('#totalSchoolStudents').html(response['schoolPublic'].number_of_boys + response['schoolPublic'].number_of_girls);
                        $('#schoolBoys').html('');
                        $('#schoolBoys').html(response['schoolPublic'].number_of_boys);
                        $('#schoolGirls').html('');
                        $('#schoolGirls').html(response['schoolPublic'].number_of_girls);
                        $('#gradeFrom').html('');
                        $('#gradeFrom').html(response['schoolPublic'].grade_from);
                        $('#gradeTo').html('');
                        $('#gradeTo').html(response['schoolPublic'].grade_to);
                    }
 
                    if(response['publicStructure'].public_structure_category_id1 == 5 ||
                        response['publicStructure'].public_structure_category_id2 == 5 ||
                        response['publicStructure'].public_structure_category_id3 == 5)  {

                            $("#kindergartenDetails").css("visibility", "visible");
                            $("#kindergartenDetails").css('display', 'block');
                            $('#totalKindergartenStudents').html('');
                            $('#totalKindergartenStudents').html(response['publicStructure'].kindergarten_students);
                            $('#kindergartenBoys').html('');
                            $('#kindergartenBoys').html(response['publicStructure'].kindergarten_male);
                            $('#kindergartenGirls').html('');
                            $('#kindergartenGirls').html(response['publicStructure'].kindergarten_female);
                    }
                }
            });
        });

        // Move household
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
 
        // Move public
        $('#miscHouseholdsTable').on('click', '.moveMISCPublic',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to start working?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('moveMISCPublic') }}",
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

        // Back household to energy request
        $('#miscHouseholdsTable').on('click', '.backMISCHousehold',function() {

            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to back this to requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('backMISCHousehold') }}",
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

        // Back public to energy request
        $('#miscHouseholdsTable').on('click', '.backMISCPublic',function() {

            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to back this to requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('backMISCPublic') }}",
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

        // Add notes for misc household
        $('#miscHouseholdsTable').on('click', '.notesMISCHousehold', function () {
            var id = $(this).data('id');

            // Get the existing note from the current row
            var currentRow = $(this).closest('tr');
            var existingNote = table.row(currentRow).data().confirmation_notes || '';

            Swal.fire({
                title: 'Edit Note',
                input: 'textarea',
                inputValue: existingNote, // Pre-fill the textarea
                inputPlaceholder: 'Type your note here...',
                inputAttributes: {
                    'aria-label': 'Type your note here'
                },
                showCancelButton: true,
                confirmButtonText: 'Save Note'
            }).then((result) => {
                if (result.isConfirmed) {
                    var note = result.value;

                    if (!note || note.trim() === "") {
                        Swal.fire('Note cannot be empty.', '', 'error');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('notesMISCHousehold') }}",
                        type: 'GET', // Use POST if you're updating data
                        data: {
                            id: id,
                            note: note
                        },
                        success: function (response) {
                            if (response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    confirmButtonText: 'Okay!'
                                }).then(() => {
                                    table.draw(); // Refresh the DataTable
                                });
                            } else {
                                Swal.fire('Failed to save note.', '', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Server error. Please try again.', '', 'error');
                        }
                    });
                }
            });
        });

        // Add notes for misc public
        $('#miscHouseholdsTable').on('click', '.notesMISCPublic', function () {
            var id = $(this).data('id');

            // Get the existing note from the current row
            var currentRow = $(this).closest('tr');
            var existingNote = table.row(currentRow).data().confirmation_notes || '';

            Swal.fire({
                title: 'Edit Note',
                input: 'textarea',
                inputValue: existingNote, // Pre-fill the textarea
                inputPlaceholder: 'Type your note here...',
                inputAttributes: {
                    'aria-label': 'Type your note here'
                },
                showCancelButton: true,
                confirmButtonText: 'Save Note'
            }).then((result) => {
                if (result.isConfirmed) {
                    var note = result.value;

                    if (!note || note.trim() === "") {
                        Swal.fire('Note cannot be empty.', '', 'error');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('notesMISCPublic') }}",
                        type: 'GET', 
                        data: {
                            id: id,
                            note: note
                        },
                        success: function (response) {
                            if (response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    confirmButtonText: 'Okay!'
                                }).then(() => {
                                    table.draw(); // Refresh the DataTable
                                });
                            } else {
                                Swal.fire('Failed to save note.', '', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Server error. Please try again.', '', 'error');
                        }
                    });
                }
            });
        });

    });
</script>
@endsection