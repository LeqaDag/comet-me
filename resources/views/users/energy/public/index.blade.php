@extends('layouts/layoutMaster')

@section('title', 'energy users')

@include('layouts.all')

@section('content')

<div class="row mb-4">
    <div class="col-lg-12 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Energy Public Facilities</h5>
            </div>
            <div class="card-body pb-2">
                <div class="d-flex justify-content-around align-items-center flex-wrap mb-4">
                    <div class="user-analytics text-center me-2">
                        <i type="solid" class="bx bx-buildings me-1"></i>
                        <span>Schools</span> 
                        <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$schools}}</h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-face me-1"></i>
                    <span>Kindergarten</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$kindergarten}}</h5>
                    </div>
                </div>
                <div class="user-analytics text-center me-2">
                    <i class="bx bx-clinic me-1"></i>
                    <span>Clinics</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$clinics}}</h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-arch me-1"></i>
                    <span>Mosques</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$mosques}}</h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-building me-1"></i>
                    <span>Madafah</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$madafah}}</h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-store-alt me-1"></i>
                    <span>Community Center</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$center}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Electricity Public Structures Issues</h5>
                </div>
                <div class="card-body">
                    <div id="energyPublicStructuresChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Public Structures Meters
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif
 
@include('users.energy.public.details')

<div class="container mb-4">
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMeterPublic">
                        Create New Public Structure	Meter
                    </button>
                    @include('users.energy.public.create')
                </div>
            @endif
            <table id="energyPublicStructuresTable" 
                class="table table-striped data-table-energy-public-structures my-2">
                <thead>
                    <tr>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
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


<script type="text/javascript">

    $(function () {

        var analyticsPublic = <?php echo $energy_public_structures; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var dataPublic = google.visualization.arrayToDataTable(analyticsPublic);

            var options = {
                title: "",
            };

            var chartPublic = new google.charts.Bar(document.getElementById('energyPublicStructuresChart'));
            chartPublic.draw(
                dataPublic, 
                options,
            );
        }

        var table = $('.data-table-energy-public-structures').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-public.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });

        
        // View record update page
        $('#energyPublicStructuresTable').on('click', '.updateEnergyPublic',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'energy_public/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energyPublicStructuresTable').on('click', '.viewEnergyPublic',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-public/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#energyPublicModalTitle').html(" ");
                    $('#englishNamePublic').html(" ");
                    $('#communityPublic').html(" ");
                    $('#meterActivePublic').html(" ");
                    $('#meterCasePublic').html(" ");
                    $('#systemNamePublic').html(" ");
                    $('#systemTypePublic').html(" ");
                    $('#systemLimitPublic').html(" ");
                    $('#systemDatePublic').html(" ");
                    $('#systemNotesPublic').html(" ");

                    $('#energyPublicModalTitle').html(response['public'].english_name);
                    $('#englishNamePublic').html(response['public'].english_name);
                    $('#communityPublic').html(response['community'].english_name);
                    $('#meterActivePublic').html(response['energyPublic'].meter_active);
                    $('#meterCasePublic').html(response['meter'].meter_case_name_english);
                    $('#systemNamePublic').html(response['system'].name);
                    $('#systemTypePublic').html(response['type'].name);
                    $('#systemLimitPublic').html(response['energyPublic'].daily_limit);
                    $('#systemDatePublic').html(response['energyPublic'].installation_date);
                    $('#systemNotesPublic').html(response['energyPublic'].notes);
                    if(response['vendor']) $('#vendorDatePublic').html(response['vendor'].name);
                    $('#installationTypePublic').html(" ");
                    if(response['installationType']) $('#installationTypePublic').html(response['installationType'].type);

                    $('#donorsDetails').html(" ");
                    if(response['energyMeterDonors'] != []) {
                        for (var i = 0; i < response['energyMeterDonors'].length; i++) {
                            if(response['energyMeterDonors'][i].donor_name == "0")  {
                                response['energyMeterDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#donorsDetails").append(
                            '<ul><li>'+ response['energyMeterDonors'][i].donor_name +'</li></ul>');  
                        }
                    }
                }
            });
        });

        // Delete record
        $('#energyPublicStructuresTable').on('click', '.deleteEnergyPublic',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this public structure?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergyPublic') }}",
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
                                    $('#energyPublicStructuresTable').DataTable().draw();
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