@extends('layouts/layoutMaster')

@section('title', 'all request energy systems')

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
                        action="{{ route('energy-request-household.export') }}">
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
  <span class="text-muted fw-light">All </span> Requested Households
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
                        <label class='col-md-12 control-label'>Filter By Status</label>
                        <select name="status" class="selectpicker form-control" 
                            data-live-search="true" id="filterByStatus">
                            <option disabled selected>Choose one...</option>
                            <option value="served">Served</option>
                            <option value="service_requested">Service requested</option>
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By System Type</label>
                        <select name="energy_system_type_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterBySystemType">
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Request Date</label>
                        <input type="date" name="date" class="form-control"
                            id="filterByRequestedDate">
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

        <div class="card-header">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 5)
                <div style="margin-top:18px">
                    <a type="button" class="btn btn-success" 
                        href="{{url('energy-request', 'create')}}" >
                        Create New Request System
                    </a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table id="energyRequestTable" class="table table-striped data-table-energy-request my-2">
                <thead>
                    <tr>
                        <th class="text-center">Requested Household</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Request Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Shared</th>
                        <th class="text-center">Referred By</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:40px">
  <span class="text-muted fw-light">All </span> Deleted Requested Households
</h4>

@if(session()->has('message_info'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message_info') }}
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
                            data-live-search="true" id="filterByCommunityDeleted">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By System Type</label>
                        <select name="energy_system_type_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterBySystemTypeDeleted">
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButtonDeleted">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="energyDeletedRequestTable" class="table table-striped data-table-energy-delete-request my-2">
                <thead>
                    <tr>
                        <th class="text-center">Deleted Household</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Deleted By</th>
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

        var table;
        function DataTableContent() {
            table = $('.data-table-energy-request').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('energy-request.index') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.community_filter = $('#filterByCommunity').val();
                        d.system_type_filter = $('#filterBySystemType').val();
                        d.date_filter = $('#filterByRequestedDate').val();
                        d.household_status = $('#filterByStatus').val();
                    }
                },
                columns: [
                    {data: 'english_name', name: 'english_name'},
                    {data: 'community_name', name: 'community_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'type', name: 'type'},
                    {data: 'referred_by', name: 'referred_by'},
                    {data: 'action'}
                ] 
            });
        }

        $(function () {
            DataTableContent();
            
            $('#filterBySystemType').on('change', function() {
                table.ajax.reload(); 
            });
            $('#filterByRequestedDate').on('change', function() {
                table.ajax.reload(); 
            });
            $('#filterByCommunity').on('change', function() {
                table.ajax.reload(); 
            });
            $('#filterByStatus').on('change', function() {
                table.ajax.reload(); 
            });

            // Clear Filter
            $('#clearFiltersButton').on('click', function() {

                $('.selectpicker').prop('selectedIndex', 0);
                $('.selectpicker').selectpicker('refresh');
                $('#filterByRequestedDate').val(' ');
                if ($.fn.DataTable.isDataTable('.data-table-energy-request')) {
                    $('.data-table-energy-request').DataTable().destroy();
                }
                DataTableContent();
            });
        });
         
        // Clear Filters for Export
        $('#clearRequestedFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByRequestedDateExport').val(' ');
        });

        // View record details
        $('#energyRequestTable').on('click', '.viewEnergyRequest',function() {

            var id = $(this).data('id');
            window.open('/household?id=' + id, '_blank'); 
        }); 

        // Move record
        $('#energyRequestTable').on('click', '.moveEnergyRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to confirm this requested household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('moveEnergyRequest') }}",
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
                                    $('#energyRequestTable').DataTable().draw();
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

        // Postponed record
        $('#energyRequestTable').on('click', '.postponedEnergyRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to postpone this requested household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm',
                input: 'textarea', 
                inputPlaceholder: 'Enter your reason for postponing this requested household...',
                inputAttributes: {
                    'aria-label': 'Enter your reason'
                },
                showCancelButton: true,  
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;  
                    $.ajax({
                        url: "{{ route('postponedEnergyRequest') }}",
                        type: 'get',
                        data: {
                            id: id,
                            reason: reason
                        },
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#energyRequestTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    console.log('Deletion canceled');
                }
            });
        });

        // Delete record
        $('#energyRequestTable').on('click', '.deleteEnergyRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to remove this requested household from the list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm',
                input: 'textarea', 
                inputPlaceholder: 'Enter your reason for deleting this requested household...',
                inputAttributes: {
                    'aria-label': 'Enter your reason'
                },
                showCancelButton: true,  
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if(result.isConfirmed) {
                    const reason = result.value; 
                    $.ajax({
                        url: "{{ route('deleteEnergyRequest') }}",
                        type: 'get',
                        data: {
                            id: id,
                            reason: reason
                        },
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#energyRequestTable').DataTable().draw();
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


        // This code for the deleted requested households 
        var table1;
        function DataTableContent1() {

            table1 = $('.data-table-energy-delete-request').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('energy-delete-request.index') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.community_deleted_filter = $('#filterByCommunityDeleted').val();
                        d.system_type_deleted_filter = $('#filterBySystemTypeDeleted').val();
                    }
                },
                columns: [
                    {data: 'english_name', name: 'english_name'},
                    {data: 'community_name', name: 'community_name'},
                    {data: 'reason', name: 'reason'},
                    {data: 'referred_by', name: 'referred_by'},
                    {data: 'action'}
                ] 
            });
        }

        $(function () {
            DataTableContent1();
            
            $('#filterBySystemTypeDeleted').on('change', function() {
                table.ajax.reload(); 
            });
            $('#filterByCommunityDeleted').on('change', function() {
                table.ajax.reload(); 
            });

            // Clear Filter
            $('#clearFiltersButtonDeleted').on('click', function() {

                $('.selectpicker').prop('selectedIndex', 0);
                $('.selectpicker').selectpicker('refresh');
                if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                    $('.data-table-energy-delete-request').DataTable().destroy();
                }
                DataTableContent1();
            });
        });

        // Return record
        $('#energyDeletedRequestTable').on('click', '.returnEnergyDeletedRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to return this deleted household to the requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm',
                showCancelButton: true,  
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;  
                    $.ajax({
                        url: "{{ route('returnEnergyDeletedRequest') }}",
                        type: 'get',
                        data: {
                            id: id,
                            reason: reason
                        },
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#energyDeletedRequestTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    console.log('Returning canceled');
                }
            });
        });

        // Delete record
        $('#energyDeletedRequestTable').on('click', '.deleteEnergyDeletedRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to remove this deleted household from the list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm',
                showCancelButton: true,  
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if(result.isConfirmed) {
                    const reason = result.value; 
                    $.ajax({
                        url: "{{ route('deleteEnergyDeletedRequest') }}",
                        type: 'get',
                        data: {
                            id: id,
                            reason: reason
                        },
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#energyDeletedRequestTable').DataTable().draw();
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