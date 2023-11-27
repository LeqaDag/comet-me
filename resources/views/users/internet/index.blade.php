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
    <div class="row mb-4">
        <div class="col-lg-12 col-xl-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Internet Users</h5>
                </div>
                <div class="card-body pb-2">
                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
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
                                        <span class="text-muted">
                                        {{$internetPercentage}} %
                                        </span> 
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width: {{$internetPercentage}}%" 
                                        role="progressbar" aria-valuenow="{{$internetPercentage}}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{$allInternetPeople}}"></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center gap-3 w-100">
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
                                    <h5 class="mb-0">{{$activeInternetCommuntiiesCount}}</h5>
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
                                        {{$data["total_cash_income"]}}
                                    @endforeach
                                </h3>
                                <span class="text-muted">Total Cash</span>
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
            @foreach($clusters as $cluster)
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
        </div>
    </div>
</div>

<div class="collapse multi-collapse container mb-4" id="collapseInternetUsersExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Export Internet User Report
                            <i class='fa-solid fa-file-excel text-info'></i>
                        </h5>
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
                                    class="form-control" title="Data from"> 
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
       
        <div class="card-body">
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

    $(function () {
        var table = $('.data-table-internet-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'start_date', name: 'start_date'},
                {data: 'action'}
            ]
        });
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

</script>
@endsection