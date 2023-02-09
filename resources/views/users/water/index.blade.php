@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-users')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="col-lg-12 col-12">
        <div class="row">
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-info">
                            <i class="bx bx-droplet fs-4"></i></span>
                        </div>
                        <span class="d-block text-nowrap">Water beneficiaries</span>
                        <h2 class="mb-0">{{$totalWaterHouseholds->number_of_people}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-male fs-4"></i>
                            </span>
                        </div>
                        <span class="d-block text-nowrap">Male</span>
                        <h2 class="mb-0">{{$totalWaterMale->number_of_male}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-danger">
                                <i class="bx bx-female fs-4"></i>
                            </span>
                        </div>
                        <span class="d-block text-nowrap">Female</span>
                        <h2 class="mb-0">{{$totalWaterFemale->number_of_female}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                <i class="bx bx-male fs-4"></i>
                                <i class="bx bx-female fs-4"></i>
                            </span>
                        </div>
                        <span class="d-block text-nowrap">Adults</span>
                        <h2 class="mb-0">{{$totalWaterAdults->number_of_adults}}</h2>
                    </div>
                </div>
            </div>   
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-dark">
                                <i class="bx bx-face fs-4"></i>
                            </span>
                        </div>
                        <span class="d-block text-nowrap">Children</span>
                        <h2 class="mb-0">{{$totalWaterChildren->number_of_children}}</h2>
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
                    <h5>Classic H2O System</h5>
                </div>
                <div class="card-body">
                    <div id="h2oUserChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Grid Integration System</h5>
                </div>
                <div class="card-body">
                    <div id="gridUserChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water System Holders
</h4>


<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createWaterUser">
                    Create New Water System Holder	
                </button>

                @include('users.water.create')
            </div>
            <table id="waterUsersTable" 
                class="table table-striped data-table-water-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Number of H2O</th>
                        <th class="text-center">H2O Status</th>
                        <th class="text-center">Number of Grid Large</th>
                        <th class="text-center">Number of Grid Small</th>
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

        var analytics = <?php echo $h2oChartStatus; ?>;
        var analyticsGrid = <?php echo $gridChartStatus; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var dataGrid = google.visualization.arrayToDataTable(analyticsGrid);

            var chart = new google.charts.Bar(document.getElementById('h2oUserChart'));
            chart.draw(
                data
            );
            var chartGrid = new google.charts.Bar(document.getElementById('gridUserChart'));
            chartGrid.draw(
                dataGrid
            );
        }

        // DataTable
        var table = $('.data-table-water-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'number_of_h20', name: 'number_of_h20'},
                {data: 'status', name: 'status'},
                {data: 'grid_integration_large', name: 'grid_integration_large'},
                {data: 'grid_integration_small', name: 'grid_integration_small' },
                {data: 'action'}
            ],
            
        });
    });
</script>
@endsection