@extends('layouts/layoutMaster')

@section('title', 'all request water systems')

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
        data-target="#collapseWaterRequestExport" aria-expanded="false" 
        aria-controls="collapseWaterRequestExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>

<div class="collapse multi-collapse container mb-4" id="collapseWaterRequestExport">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export Water Requested Systems Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearWaterRequestedFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('water-request.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Community</option>
                                            @foreach($communities as $community)
                                            <option value="{{$community->id}}">
                                                {{$community->english_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Status of request</label>
                                        <select name="request_status"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Status of request</option>
                                            @foreach($requestStatuses as $requestStatus)
                                            <option value="{{$requestStatus->id}}">
                                                {{$requestStatus->name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Requested Date</label>
                                        <input type="date" name="date" id="waterRequestedDateFilter"
                                        class="form-control" title="Data from"> 
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
  <span class="text-muted fw-light">All </span> Water Requested Systems
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
            <div style="margin-top:18px">
                <a type="button" class="btn btn-success" 
                    href="{{url('water-request', 'create')}}" >
                    Create New Request System
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="waterRequestTable" class="table table-striped data-table-water-request my-2">
                <thead>
                    <tr>
                        <th>Requested Household/Public Structure</th>
                        <th>Community</th>
                        <th>Requested Date</th>
                        <th>Main Energy User?</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('request.water.show')

<script type="text/javascript">

    var table;
    function DataTableContent() {
            
        table = $('.data-table-water-request').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-request.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'holder', name: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'date', name: 'date'},
                {data: 'is_main', name: 'is_main'},
                {data: 'action'}
            ]
        });
    };

    $(function () {

        DataTableContent();
        
        // View record details
        $('#waterRequestTable').on('click', '.viewWaterRequest',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'water-request/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) { 

                    $('#requestWaterModalTitle').html(" ");
                    $('#englishNameUser').html(" ");

                    if(response['household']) {

                        $('#requestWaterModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(response['household'].english_name);
                    } else if(response['public']) {

                        $('#requestWaterModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(response['public'].english_name);
                    }
                    
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterNumberUser').html(" ");
                    $('#meterCaseUser').html(" ");
                    $('#systemTypeUser').html(" ");
                    $('#systemLimitUser').html(" ");
                    $('#installationDate').html(" ");

                    if(response['energy']) {

                        $('#meterNumberUser').html(response['energy'].meter_number);
                        $('#meterCaseUser').html(response['meter'].meter_case_name_english);
                        $('#systemTypeUser').html(response['type'].name);
                        $('#systemLimitUser').html(response['energy'].daily_limit);
                        $('#installationDate').html(response['energy'].installation_date);
                    } 

                    $('#waterRequestedDate').html(" ");
                    $('#waterRequestedDate').html(response['waterRequestSystem'].date);
                    $('#waterRequestedType').html(" ");
                    $('#waterRequestedType').html(response['waterRequestSystemType'].type);
                    $('#waterRequestStatusCase').html(" ");
                    $('#waterRequestStatusCase').html(response['waterRequestStatus'].name);
                    $('#referredBy').html(" ");
                    $('#referredBy').html(response['waterRequestSystem'].referred_by);
                    $('#systemNotesUser').html(" ");
                    $('#systemNotesUser').html(response['waterRequestSystem'].notes);

                    waterRequestedDate
                }
            });
        }); 

        // Delete record
        $('#waterRequestTable').on('click', '.deleteWaterRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this water requested household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterRequest') }}",
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
                                    $('#waterRequestTable').DataTable().draw();
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

        // Move record
        $('#waterRequestTable').on('click', '.moveWaterRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to work for this requested holder?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('moveWaterRequest') }}",
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
                                    $('#waterRequestTable').DataTable().draw();
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

        // View record photos
        $('#waterRequestTable').on('click', '.updateWaterRequest',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Clear Filters for Export
        $('#clearWaterRequestedFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#waterRequestedDateFilter').val(' ');
        });
    });
</script>
@endsection