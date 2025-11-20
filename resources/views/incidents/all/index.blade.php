@extends('layouts/layoutMaster')

@section('title', 'all incidents')

@include('layouts.all')

@section('content')

@include('employee.incident_details')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseAllIncidentExport" aria-expanded="false" 
        aria-controls="collapseAllIncidentExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseAllIncidentExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export All Incidents Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearAllIncidentsFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('all-incident.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="service_ids[]" 
                                            class="selectpicker form-control" data-live-search="true" multiple>
                                            <option disabled selected>Select Services</option>
                                            @foreach($serviceTypes as $serviceType)
                                                <option value="{{$serviceType->id}}">
                                                    {{$serviceType->service_name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Select Community</option>
                                            @foreach($communities as $community)
                                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="incident_id" 
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Select Incident</option>
                                            @foreach($incidents as $incident)
                                            <option value="{{$incident->id}}">
                                                {{$incident->english_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <input type="date" name="date" id="incidentDate"
                                        class="form-control" title="Incident Data from"> 
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px">
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

    @if(Auth::guard('user')->user()->user_type_id == 1 ||  
        Auth::guard('user')->user()->user_type_id == 2 )
        <div class="container mb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xl-10 col-lg-10 col-md-10">
                                    <h5>
                                    Export All Aggregated Incidents
                                        <i class='fa-solid fa-file-excel text-info'></i>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('all-aggregated-incident.export') }}">
                            @csrf
                            <div class="card-body"> 
                                <!-- <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <select name="service_ids1[]" 
                                                class="selectpicker form-control" data-live-search="true" multiple>
                                                <option disabled selected>Select Services</option>
                                                @foreach($serviceTypes as $serviceType)
                                                    <option value="{{$serviceType->id}}">
                                                        {{$serviceType->service_name}}
                                                    </option>
                                                @endforeach
                                            </select> 
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <select name="community_id1" class="selectpicker form-control" 
                                                data-live-search="true">
                                                <option disabled selected>Select Community</option>
                                                @foreach($communities as $community)
                                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                                @endforeach
                                            </select> 
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <select name="incident_id1" 
                                                class="selectpicker form-control" data-live-search="true">
                                                <option disabled selected>Select Incident</option>
                                                @foreach($incidents as $incident)
                                                <option value="{{$incident->id}}">
                                                    {{$incident->english_name}}
                                                </option>
                                                @endforeach
                                            </select> 
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <input type="date" name="date1" id="incidentAggregatedDate"
                                            class="form-control" title="Incident Data from"> 
                                        </fieldset>
                                    </div>
                                </div> -->
                                <div class="row">
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
    @endif
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Incidents
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
                        <label class='col-md-12 control-label'>Filter By Service</label>
                        <select name="" id="filterByService"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Filter By Service</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{$serviceType->id}}">
                                    {{$serviceType->service_name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
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
                        <label class='col-md-12 control-label'>Filter By Incident</label>
                        <select name="" id="filterByIncident"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Filter By Incident</option>
                            @foreach($incidents as $incident)
                            <option value="{{$incident->id}}">
                                {{$incident->english_name}}
                            </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Incident Date</label>
                       <input type="date" class="form-control" id="filterByDate">
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||  
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->role_id == 21)
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button type="button" class="btn btn-warning" 
                            id="getLatestTickets">
                            Get Latest Incident Tickets
                        </button>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3" id="loader" style="display: none;">
                        <p>Loading...</p> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <a type="button" class="btn btn-success" 
                            href="{{url('all-incident', 'create')}}">
                            Add New Incident	
                        </a>
                    </div>
                </div>
            @endif
            <table id="allIncidentsTable" class="table table-striped data-table-all-incidents my-2">
                <thead>
                    <tr>
                        <th>Community</th>
                        <th>Holder (User/ Public) or System</th>
                        <th>Statuses</th>
                        <th>Service</th>
                        <th>Incident</th>
                        <th>Date</th>
                        <th>Options</th>
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

        table = $('.data-table-all-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-incident.index') }}",
                data: function (d) {
                
                    d.service_filter = $('#filterByService').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.incident_filter = $('#filterByIncident').val();
                    d.date_filter = $('#filterByDate').val();
                }
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'excel',
                }
            ],
            columns: [
                {data: 'community_name', name: 'community_name'},
                {data: 'holder', name: 'holder'},
                {data: 'incident_statuses', name: 'incident_statuses'},
                {data: 'service', name: 'service'},
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'action'}
            ]
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByDate').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByIncident').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByService').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDate').val('');
            if ($.fn.DataTable.isDataTable('.data-table-all-incidents')) {
                $('.data-table-all-incidents').DataTable().destroy();
            }
            DataTableContent();
        });
         
        // Clear Filters for Export
        $('#clearAllIncidentsFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#incidentDate').val(' ');
        });

        // Clear Filters for Export
        $('#clearAllAggregatedIncidentsFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#incidentAggregatedDate').val(' ');
        });

        // View record details
        $('#allIncidentsTable').on('click', '.viewAllIncident', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // View record photos
        $('#allIncidentsTable').on('click', '.updateAllIncident',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#allIncidentsTable').on('click', '.deleteAllIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteAllIncident') }}",
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
                                    $('#allIncidentsTable').DataTable().draw();
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

    // Get the incident tickets
    $('#getLatestTickets').on('click', function () {
   
        $('#loader').show();

        // AJAX request
        $.ajax({
            url: 'api/ticket',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tickets Gotten Successfully!',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!'
                }).then((result) => {
                    $('#allIncidentsTable').DataTable().draw();
                });
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to fetch tickets!',
                    text: 'Please try again later.'
                });
            },
            complete: function () {
                
                $('#loader').hide();
            }
        });
    });
</script>
@endsection