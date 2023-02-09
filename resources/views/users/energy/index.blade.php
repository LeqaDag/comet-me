@extends('layouts/layoutMaster')

@section('title', 'energy users')

@include('layouts.all')

@section('content')

<div class="row mb-4">
    <div class="col-lg-12 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Energy Users</h5>
            </div>
            <div class="card-body pb-2">
                <div class="d-flex justify-content-around align-items-center flex-wrap mb-4">
                    <div class="user-analytics text-center me-2">
                        <i class="bx bx-bulb me-1"></i>
                        <span>Meter Users</span>
                        <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$energyUsersNumbers}}</h5>
                    </div>
                </div>
                <div class="user-analytics text-center me-2">
                    <i class="bx bx-hive me-1"></i>
                    <span>Shared Users</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$householdMeterNumbers}}</h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-grid-alt me-1"></i>
                    <span>MG Energy Users</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$energyMgNumbers}}</h5>
                    </div>
                </div>
                <div class="bounce-rate-analytics text-center">
                    <i class="bx bx-group me-1"></i>
                    <span>FBS Energy Users</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$energyFbsNumbers}}</h5>
                    </div>
                </div>
                <div class="bounce-rate-analytics text-center">
                    <i class="bx bx-grid me-1"></i>
                    <span>MMG Energy Users</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0">{{$energyMmgNumbers}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-12 col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Energy Public Structures</h5>
            </div>
            <div class="card-body pb-2">
                <div class="d-flex justify-content-around align-items-center flex-wrap mb-4">
                    <div class="user-analytics text-center me-2">
                        <i type="solid" class="bx bx-buildings me-1"></i>
                        <span>Schools</span>
                        <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0"></h5>
                    </div>
                </div>
                <div class="user-analytics text-center me-2">
                    <i class="bx bx-clinic me-1"></i>
                    <span>Clinics</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0"></h5>
                    </div>
                </div>
                <div class="sessions-analytics text-center me-2">
                    <i class="bx bx-arch me-1"></i>
                    <span>Mosques</span>
                    <div class="d-flex align-items-center mt-2">
                        <h5 class="mb-0"></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Electricity Meter Users</h5>
                </div>
                <div class="card-body">
                    <div id="energyUserChart"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Electricity Public Structures</h5>
                </div>
                <div class="card-body">
                    <div id="energyPublicStructuresChart"></div>
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

<div class="container mb-4">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createMeterUser">
                    Create New Meter Holder	
                </button>
                @include('users.energy.create')
            </div>
            <table id="energyUsersTable" class="table table-striped data-table-energy-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
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

        var analytics = <?php echo $energy_users; ?>;
        var analyticsPublic = <?php echo $energy_public_structures; ?>

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var dataPublic = google.visualization.arrayToDataTable(analyticsPublic);

            var options = {
                title: "Available Products",
            };

            var chart = new google.charts.Bar(document.getElementById('energyUserChart'));
            chart.draw(
                data, 
                options,
            );

            var chartPublic = new google.charts.Bar(document.getElementById('energyPublicStructuresChart'));
            chartPublic.draw(
                dataPublic, 
                options,
            );
        }

        var table = $('.data-table-energy-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });
        
    });
</script>
@endsection