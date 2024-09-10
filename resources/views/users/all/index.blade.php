@extends('layouts/layoutMaster')

@section('title', 'all users')

@include('layouts.all')

@section('content')

<div class="Container mb-4">
    <div class="col-lg-12 col-md-12 mb-4">
        <div class="">
            <div class="pb-2">
                <div class="d-flex justify-content-around align-items-center flex-wrap mb-4">
                    <div class="user-analytics text-center me-2">
                        <i class="bx bx-user me-1 text-dark"></i>
                        <span>
                            <a href="{{'household'}}" target="_blank">All Households</a></span>
                        <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="dark" 
                            data-series="100"></div>
                            <h3 class="mb-0">
                                {{$allHouseholds}}
                            </h3>
                        </div>
                    </div>
                    <div class="user-analytics text-center me-2">
                        <i class="bx bx-bulb me-1 text-warning"></i>
                        <span>
                            <a href="{{'all-meter'}}" target="_blank">Energy Users</a>
                        </span>
                        <?php
                            $energyRatio = ($energyUsers/$allHouseholds)  * 100;
                        ?>
                        <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="warning" 
                            data-series="{{$energyRatio}}"></div>
                            <h3 class="mb-0">
                                {{$energyUsers}}
                            </h3>
                        </div>
                    </div>
                    <div class="sessions-analytics text-center me-2">
                        <i class="bx bx-water me-1 text-info"></i>
                        <span>
                            <a href="{{'all-water'}}" target="_blank">Water Users</a></span>
                        <?php
                            $waterRatio = ($waterUsers/$allHouseholds)  * 100;
                        ?>
                        <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="info" 
                                data-series="{{$waterRatio}}"></div>
                            <h3 class="mb-0">{{$waterUsers}}</h3>
                        </div>
                    </div>
                    <div class="bounce-rate-analytics text-center">
                        <i class="bx bx-wifi me-1 text-success"></i>
                        <span>
                            <a href="{{'internet-user'}}" target="_blank">Internet Users</a></span>
                        <?php
                            $internetRatio = ($internetUsers/$allHouseholds)  * 100;
                        ?>
                        <div class="d-flex align-items-center mt-2">
                        <div class="chart-report" data-color="success" 
                            data-series="{{$internetRatio}}">
                        </div>
                        <h3 class="mb-0">{{$internetUsers}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Active Users
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
        <!-- <div class="card-header">
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('all-active.export') }}">
                @csrf
            </form>
        </div> --> 
        <div class="card-header">
            <div class="col-xl-4 col-lg-4 col-md-4">
                <fieldset class="form-group">
                    <label class='col-md-12 control-label'>Filter By Service</label>
                    <select name="service_served" id="filterByServedService"
                        class="form-control">
                        <option disabled selected>Choose one...</option>
                        <option value="all">Energy Served</option>
                        <option value="water">Water Served</option>
                        <option value="internet">Internet Served</option>
                    </select> 
                </fieldset>
            </div>
        </div>
        <div class="card-body">
            <table id="allActiveUsersTable" class="table table-striped data-table-all-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Water Service</th>
                        <th class="text-center">Internet Service</th>
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

        var table = $('.data-table-all-users').DataTable({
            processing: true,
            serverSide: true, 
            ajax: { 
                url: "{{ route('all-active.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByServedService').val();
                }
            },
            columns: [ 
                {data: 'household_name', name: 'household_name'},
                {data: 'region', name: 'region'},
                {data: 'community_name', name: 'community_name'},
                {data: 'water_system_status', name: 'water_system_status'},
                {data: 'internet_system_status', name: 'internet_system_status'},
            ]
        });

        $('#filterByServedService').on('change', function() {
            table.ajax.reload(); // Reload DataTable when the dropdown value changes
        });
    });
</script>
@endsection