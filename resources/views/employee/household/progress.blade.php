@extends('layouts/layoutMaster')

@section('title', 'In Progress households')

@include('layouts.all')

@section('content')

<div class="container mb-4 my-2"> 
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-header">
                    <h5>In Progress Households by Community</h5>
                </div>
                <div class="panel-body" >
                    <div id="community_progress_households_chart" style="height:300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span>In Progress Households
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif 

@include('employee.household.sub_household')
@include('employee.household.details')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <a type="button" class="btn btn-success" 
                            href="{{url('progress-household', 'create')}}" >
                            Create New Elc. / In Progress Household
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('energy-request.export') }}">
                            @csrf
                            <button class="btn btn-info" type="submit">
                                <i class='fa-solid fa-file-excel'></i>
                                Export Energy Summary Report 
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            </div>
            <table id="progressHouseholdsTable" 
                class="table table-striped data-table-progress-households my-2">
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
    $(function () {

        var analytics = <?php echo $communityAcHouseholdsData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'In Progress Households by Community' 
            };

            var chart = new google.charts.Bar(document.getElementById('community_progress_households_chart'));
            chart.draw(data, options);
        }

        var table = $('.data-table-progress-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('progress-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'region_name', name: 'region_name'},
                {data: 'action'}
            ]
        });

        // Change status 
        $('#acHouseholdsTable').on('change', '.sharedHousehold',function() {
            var id = $(this).data('id');
            var isShared = $(this).val();
 
            if(isShared == "No" ){
                $.ajax({
                    url: "{{ route('acSubHousehold') }}",
                    type: 'get',
                    data: {
                        id: id,
                        isShared: isShared
                    },
                    success: function(response) {
                        $('#subHouseholdModal').modal('toggle');
                        $(".mainHousehold").html("");
                        response.forEach(el => {
                            $(".mainHousehold").append(`<option value='${el.id}'> ${el.english_name}</option>`)
                        }); 

                        $('#btn_save').click(function (e) {
                            e.preventDefault();
                            var user_id = $('#mainHousehold').val();
                        
                            $.ajax({
                                url: "{{ route('acSubHouseholdSave') }}",
                                type: 'get',
                                data: {
                                    id: id,
                                    user_id: user_id,
                                },
                                success: function (data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: "Household Meter Updated Successfully!",
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay!'
                                    }).then((result) => {
                                        $('#acHouseholdsTable').DataTable().draw();
                                    });

                                    $('#subHouseholdModal').modal('hide');
                                    table.draw();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn_save').html('Save Changes');
                                }
                            });
                        });
                    }
                });
            } else if(isShared == "Yes") {
                $.ajax({
                    url: "{{ route('acMainHousehold') }}",
                    type: 'get',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: "Energy User Updated Successfuly!",
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {
                            $('#acHouseholdsTable').DataTable().draw();
                        });
                    }
                });
            }
           

            // Swal.fire({
            //     icon: 'warning',
            //     title: 'Are you sure you want to change the status for this household to Served?',
            //     showDenyButton: false,
            //     showCancelButton: true,
            //     confirmButtonText: 'Confirm'
            // }).then((result) => {
               
            // });
        });
    
        // View record details
        $('#progressHouseholdsTable').on('click', '.updateHousehold',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'progress-household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, '_self'); 
                }
            });
        });

        // View record details
        $('#progressHouseholdsTable').on('click', '.detailsHouseholdButton',function() {
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
    
        // Delete record
        $('#progressHouseholdsTable').on('click', '.deleteHousehold',function() {
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
                                    $('#progressHouseholdsTable').DataTable().draw();
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