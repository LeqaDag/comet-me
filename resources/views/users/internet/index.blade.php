@extends('layouts/layoutMaster')

@section('title', 'all internet users')

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseInternetUsersVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseInternetUsersVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseInternetUsersExport" aria-expanded="false" 
        aria-controls="collapseInternetUsersExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseInternetUsersVisualData collapseInternetUsersExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse container mb-4" id="collapseInternetUsersVisualData">
       <!-- Internet Users -->

    <div class="card mb-4">
        <div class="card-body">
            <h5>Internet Users</h5>
            <div class="row">
                <div class="">
                    <div class="">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-2">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <a href="{{'internet-user'}}" target="_blank" type="button"> 
                                            <i class='bx bx-wifi'></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Internet Users</span>
                                        <span class="text-muted">{{$internetPercentage}}%</span> 
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width: {{$internetPercentage}}%" 
                                            role="progressbar" aria-valuenow="{{$internetPercentage}}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="{{$allInternetPeople}}">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div class="d-flex align-content-center">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <a type="button" data-bs-toggle="modal" 
                                            data-bs-target="#communityInternet">
                                            <i class='bx bx-home'></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="chart-info">
                                    <h5 class="mb-0">{{$dataJson[0]["total_active_communities"]}}</h5>
                                    <small class="text-muted">Active Communities</small>
                                </div>
                            </div>
                            @include('employee.community.service.internet')
                            <div class="d-flex align-content-center">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class='bx bx-book-content bx-large'></i>
                                    </span>
                                </div>
                                <div class="chart-info"> 
                                    <h5 class="mb-0">{{$allContractHolders}}</h5>
                                    <small class="text-muted">Contract Holders</small>
                                </div>
                            </div>
                            <div class="d-flex align-content-center">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class='bx bx-user'></i>
                                    </span>
                                </div>
                                <div class="chart-info">
                                    <h5 class="mb-0">{{$allInternetUsersCounts}}</h5>
                                    <small class="text-muted">Users</small>
                                </div>
                            </div>
                            <div class="d-flex align-content-center">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class='bx bx-happy bx-large'></i>
                                    </span>
                                </div>
                                <div class="chart-info"> 
                                    <h5 class="mb-0">{{$youngInternetHolders}}</h5>
                                    <small class="text-muted">Young Holders</small>
                                </div>
                            </div>
                            <div class="d-flex align-content-center">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class='bx bx-buildings'></i>
                                    </span>
                                </div>
                                <div class="chart-info">
                                    <h5 class="mb-0">{{$InternetPublicCount}}</h5>
                                    <small class="text-muted">Public Structures</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-body">
            <h5>Contracts Overview</h5>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_communities"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Internet Communities</span>
                                <div class="primary">
                                    <i class="bx bx-wifi me-1 bx-lg text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_active_communities"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Active Communities</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-home-smile me-1 bx-lg text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_inactive_communities"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Non-active Communities</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-home-alt me-1 bx-lg text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_sale_points"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Sale Points</span>
                                <div class="primary">
                                    <i class="bx bx-wallet me-1 bx-lg text-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_paid_cash"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Total Paid Cash</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-shekel me-1 bx-lg text-primary"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_contracts"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Total Contracts</span>
                                <div class="primary">
                                    <i class="bx bx-user-voice me-1 bx-lg text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_active_contracts"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Active Contracts</span>
                                <div class="">
                                    <a href="{{'community'}}" target="_blank" type="button">
                                    <i class="bx bx-comment-add me-1 bx-lg text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_expire_contracts"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Expire Contracts</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-comment-minus me-1 bx-lg text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_accounts_expired_less_30_days"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Expire Contracts < month</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-calendar-week me-1 bx-lg text-info"></i>
                                    </a>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">
                                    @foreach($dataJson as $data)
                                        {{$data["total_accounts_expired_over_30_days"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Expire Contracts > month</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-calendar-alt me-1 bx-lg text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-2 pt-4 pb-1">Internet Clusters</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="h-100">
                        <div class="text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-purchase-tag fs-4"></i></span>
                            </div>
                            <h6 class="mb-0 text-primary">Name</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="h-100">
                        <div class="text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-purchase-tag fs-4"></i></span>
                            </div>
                            <h6 class="mb-0 text-warning">ISP</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="h-100">
                        <div class="text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-purchase-tag fs-4"></i></span>
                            </div>
                            <h6 class="mb-0 text-info">Attached Communities</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="h-100">
                        <div class="text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-purchase-tag fs-4"></i></span>
                            </div>
                            <h6 class="mb-0 text-success">Active Contracts</h6>
                        </div>
                    </div>
                </div>
            </div>
            @if($clustersJson)
            @foreach($clustersJson as $cluster)
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="text-center">
                        <span class="d-block">{{$cluster["cluster_name"]}}</span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="text-center">
                        <span class="d-block">{{$cluster["isp"]}}</span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <div class="text-center">
                        <span class="d-block">{{$cluster["attached_communities"]}}</span>
                    </div>
                </div>
            
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="text-center">
                        <span class="d-block">{{$cluster["active_contracts"]}}</span>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-2 pt-4 pb-1">% of Non-paying Holders</h5>
        </div>
        <div class="card-body">
            <div style="width: 80%; margin: auto;">
                <canvas id="nonPayingChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-2 pt-4 pb-1">% of Non-paying Holders - Detailed</h5>
        </div>
        <div class="card-body">
            <div style="width: 80%; margin: auto;">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="collapse multi-collapse container mb-4" id="collapseInternetUsersExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Internet User Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearInternetUserFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('internet-user.export') }}">
                        @csrf
                        <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="community"
                                        class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                        <option value="{{$community->english_name}}">
                                            {{$community->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="donor"
                                        class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Search Donor</option>
                                        @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">
                                            {{$donor->donor_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <input type="date" name="start_date" 
                                     id="startDate" class="form-control" title="Data from"> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
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
  <span class="text-muted fw-light">All </span> Internet Contract Holders
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
                        <label class='col-md-12 control-label'>Filter By Network Cluster</label>
                        <select name="cluster_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByCluster">
                            <option disabled selected>Choose one...</option>
                            @foreach($internetClusters as $internetCluster)
                                <option value="{{$internetCluster->id}}">{{$internetCluster->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Installation Date</label>
                        <input type="date" name="date" class="form-control"
                            id="filterByInstallationDate">
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
            <div>
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 6 ||
                Auth::guard('user')->user()->user_type_id == 10 )
                <div style="margin-top:30px">
                    <button type="button" class="btn btn-success" 
                        id="getInternetHolders">
                        Get Latest Internet Holders
                    </button>
                </div>
            @endif
            </div>
            <table id="internetAllUsersTable" class="table table-striped data-table-internet-users my-2">
                <thead>
                    <tr>
                        <th>Contract Holder</th>
                        <th>Community</th>
                        <th>Date</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10 )
                            <th>Options</th>
                        @else
                            <th></th>
                        @endif
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
        table = $('.data-table-internet-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.cluster_filter = $('#filterByCluster').val();
                    d.date_filter = $('#filterByInstallationDate').val();
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'start_date', name: 'start_date'},
                {data: 'action'}
            ] 
        });
    }


    $(function () {
        DataTableContent();
        
        $('#filterByCluster').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByInstallationDate').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByInstallationDate').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-internet-users')) {
                $('.data-table-internet-users').DataTable().destroy();
            }
            DataTableContent();
        });
    });

    // Clear Filters for Export
    $('#clearInternetUserFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#startDate').val(' ');
    });

    // Update record
    $('#internetAllUsersTable').on('click', '.updateInternetUser',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'internet-user/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self");
            }
        });
    });
  
    // Get all Contract Holders
    $('#getInternetHolders').on('click', function() {

        // AJAX request
        $.ajax({
            url: 'api/internet-holder',
            type: 'get',
            dataType: 'json',
            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Internet Contract Holders Gotten Successfully!',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!'
                }).then((result) => {

                    $('#internetAllUsersTable').DataTable().draw();
                });
            }
        });
    });

    // Delete record
    $('#internetAllUsersTable').on('click', '.deleteInternetUser', function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this user?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetHolder') }}",
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
                                $('#internetAllUsersTable').DataTable().draw();
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

    var data = @json($percentageData);

    // Extract periods, cluster names, unpaid_percentage values, and total_contracts
    var periods = Object.keys(data);
    var clusterNames = [];
    var datasets = [];
    var colors = ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(153, 102, 255, 0.2)']; // Add more colors if needed

    // Extract cluster names, unpaid_percentage values, and total_contracts for each period
    periods.forEach(function(period) {
        var clusterData = data[period];
        Object.keys(clusterData).forEach(function(clusterName) {
            if (!clusterNames.includes(clusterName)) {
                clusterNames.push(clusterName);
            }
        });
    });

    // Prepare datasets for each cluster
    clusterNames.forEach(function(clusterName, index) {
        var dataset = {
            label: clusterName,
            data: [], 
            totalContracts: [], 
            unpaid: [], // Store total_contracts values
            backgroundColor: colors[index % colors.length], // Assign color based on index
            borderColor: colors[index % colors.length].replace('0.2', '1'), // Set border color
            borderWidth: 1,
            pointRadius: 5,
            pointHoverRadius: 7
        };
        periods.forEach(function(period) {
            var clusterData = data[period][clusterName];
            dataset.data.push(clusterData.unpaid_percentage);
            dataset.totalContracts.push(clusterData.total_contracts);
            dataset.unpaid.push(clusterData.total_unpaid); 
        });
        datasets.push(dataset);
    });

    // Create chart
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: periods, // X-axis: Periods
            datasets: datasets // Y-axis: Unpaid Percentage for each cluster
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Period (Date From - Date To)'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '% of Total Unpaid'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return periods[tooltipItems[0].dataIndex];
                        },
                        label: function(context) {
                            var clusterName = context.dataset.label;
                            var value = context.parsed.y;
                            var index = context.dataIndex;
                            var totalContracts = context.dataset.totalContracts[index]; 
                            var unpaid = context.dataset.unpaid[index]; 
                            return clusterName + ': ' + value + '% - Total Contracts: ' + totalContracts + ' ( Unpaid: ' + unpaid + ' )' ;
                        }
                    }
                }
            }
        }
    });


    var chartData = <?php echo json_encode($chartData); ?>;

    // Create a new Chart.js instance
    var ctx = document.getElementById('nonPayingChart').getContext('2d');
    var myChart1 = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'month'
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Contracts'
                    }
                }]
            }
        }
    });

</script>
@endsection