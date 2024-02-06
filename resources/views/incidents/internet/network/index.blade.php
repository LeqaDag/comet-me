@extends('layouts/layoutMaster')

@section('title', 'internet network incidents')

@include('layouts.all')
<style>
    label, input {
        display: block;
    }
    label, table {
        margin-top: 20px;
    }
</style> 

@section('content')

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseInternetNetworkIncidentVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseInternetNetworkIncidentVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseInternetNetworkIncidentExport" aria-expanded="false" 
        aria-controls="collapseInternetNetworkIncidentExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseInternetNetworkIncidentVisualData collapseInternetNetworkIncidentExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseInternetNetworkIncidentVisualData">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div id="incidentsInternetNetworkChart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse mb-4" id="collapseInternetNetworkIncidentExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Internet Network Incident Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearNetworkIncidentFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('incident-network.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="type" class="selectpicker form-control" 
                                            required>
                                            <option disabled selected>Network or All Incidents?</option>
                                            <option value="network">Network Incidents</option>
                                            <option value="all">All Incidents</option>
                                        </select> 
                                    </fieldset>
                                </div>
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
                                        <input type="date" name="date" id="incidentNetworkDate" 
                                        class="form-control" title="Data from"> 
                                    </fieldset>
                                </div>
                            </div><br>
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
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Internet Network Incidents
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||  
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 10 ||
                Auth::guard('user')->user()->user_type_id == 6)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createInternetNetworkIncident">
                        Add Internet Network Incident
                    </button>
                    @include('incidents.internet.network.create')
                </div>
            @endif
            <table id="internetNetworkIncidentsTable" 
                    class="table table-striped data-table-internet-network-incidents my-2">
                <thead>
                    <tr>
                        <th class="text-center">Community</th>
                        <th class="text-center">Incident</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
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

        var table = $('.data-table-internet-network-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('incident-network.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
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
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'name', name: 'name'},
                {data: 'action'}
            ]
        });
        
        // Clear Filters for Export
        $('#clearNetworkIncidentFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#incidentNetworkDate').val(' ');
        });

        // View record details
        $('#internetNetworkIncidentsTable').on('click', '.viewInternetNetworkIncident', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // View edit page
        $('#internetNetworkIncidentsTable').on('click', '.updateInternetNetworkIncident',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#internetNetworkIncidentsTable').on('click', '.deleteInternetNetworkIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteInternetNetworkIncident') }}",
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
                                    $('#internetNetworkIncidentsTable').DataTable().draw();
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