@extends('layouts/layoutMaster')

@section('title', 'all energy users')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Electricity Meter Issues</h5>
                </div>
                <div class="card-body">
                    <div id="energyUserChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Electricity Meter Users
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <table id="energyAllUsersTable" class="table table-striped data-table-energy-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Meter Active</th>
                        <th class="text-center">Energy System</th>
                        <th class="text-center">Energy System Type</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('users.energy.details')

<script type="text/javascript">
    $(function () {

        var analytics = <?php echo $energy_users; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var options = {
                title: "",
            };

            var chart = new google.charts.Bar(document.getElementById('energyUserChart'));
            chart.draw(
                data, 
                options,
            );
        }

        var table = $('.data-table-energy-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'meter_case_name_english', name: 'meter_case_name_english'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });

        // View Donors
        $('#energyAllUsersTable').on('click', '.donorEnergyUser',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'allMeter/donor/' +  id + '/editDonor',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url); 
                    // $('#energyDonorModalTitle').html(response.energyDonors[0].household_name);

                    // //alert(response.energyDonors.length);
                    // if(response.energyDonors.length > 1) {

                    //     for (var i = 0; i < response.energyDonors.length; i++) {
                    //         $("#donorsEnergyUser").append(
                    //         '<option>'+ response['energyDonors'][i].donor_name +'</option>');
                    //     } 
                        
                    // } else if(response.energyDonors.length == 1) {
                    //     $("#donorsEnergyUser").html(
                    //         '<option>'+ response['energyDonors'][0].donor_name +'</option>');
                        
                    // }
                }
            });
        });


        // View record details
        $('#energyAllUsersTable').on('click', '.updateAllEnergyUser',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'allMeter/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // Update record
        // $('#energyAllUsersTable').on('click', '.updateAllEnergyUser',function() {
        //     var id = $(this).data('id');

        //     // AJAX request
        //     $.ajax({
        //         url: 'allMeter/' + id,
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(response) {

        //             if(response.success == 1) {
        //                 $('#meter_number').val(response.meter_number);
        //                 $('#daily_limit').val(response.daily_limit);
        //                 $('#selectedActive').html(response.meter_active);
        //                 $('#selectedMeterCase').html(response.meter_case_id);
        //                 $('#installation_date').val(response.installation_date);

        //                 meter_active = $('#selectedActive').val();
        //                 meter_case_id = $('#selectedMeterCase').val();

        //                 $(document).on('change', '#meter_active', function () {
        //                     meter_active = $(this).val();
        //                 });

        //                 $(document).on('change', '#meter_case_id', function () {
        //                     meter_case_id = $(this).val();
        //                 });

        //                 $('#btn_save').click(function (e) {
        //                     e.preventDefault();
        //                     $(this).html('Sending..');

        //                     meter_number = $('#meter_number').val();
        //                     daily_limit = $('#daily_limit').val();
        //                     installation_date = $('#installation_date').val();

        //                     $.ajax({
        //                         data: {
        //                             id: id,
        //                             meter_number : meter_number,
        //                             installation_date : installation_date,
        //                             daily_limit : daily_limit,
        //                             meter_active : meter_active,
        //                             meter_case_id : meter_case_id,
        //                         },
        //                         url: 'allMeter/info/' + id,
        //                         type: "get",
        //                         dataType: 'json',
        //                         success: function (data) {
        //                             Swal.fire({
        //                                 icon: 'success',
        //                                 title: data.success,
        //                                 showDenyButton: false,
        //                                 showCancelButton: false,
        //                                 confirmButtonText: 'Okay!'
        //                             }).then((result) => {
        //                                 $("#energyAllUsersTable").DataTable().draw();
        //                             });

        //                             $('#subHouseholdModal').modal('hide');
        //                             table.draw();
        //                         }
        //                     });
        //                 });

        //             }
        //         }
        //     });
        // });
        
        // View record details
        $('#energyAllUsersTable').on('click','.viewEnergyUser',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-user/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#energyUserModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterActiveUser').html(response['user'].meter_active);
                    $('#meterCaseUser').html(response['meter'].meter_case_name_english);
                    $('#systemNameUser').html(response['system'].name);
                    $('#systemTypeUser').html(response['type'].name);
                    $('#systemLimitUser').html(response['user'].daily_limit);
                    $('#systemDateUser').html(response['user'].installation_date);
                    $('#systemNotesUser').html(response['user'].notes);

                    if(response['householdMeters'] != []) {
                        for (var i = 0; i < response['householdMeters'].length; i++) {
                            $.ajax({
                                url: 'household-meter/' + response['householdMeters'][i].id,
                                type: 'get',
                                dataType: 'json',
                                success: function(response) {
                                    $("#householdMeters").append(
                                    '<ul><li>'+ response['household'].english_name +'</li> </ul>');
                                }
                            });
                        }
                    }
                }
            });
        });
    });
</script>
@endsection