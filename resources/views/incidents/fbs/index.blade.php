@extends('layouts/layoutMaster')

@section('title', 'fbs incidents')

@include('layouts.all')

@section('content')

@include('system.energy.fbs_incidents_details')

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseFBSIncidentVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseFBSIncidentVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseFBSIncidentExport" aria-expanded="false" 
        aria-controls="collapseFBSIncidentExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseFBSIncidentVisualData collapseFBSIncidentExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseFBSIncidentVisualData">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div id="incidentsFbsChart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse mb-4" id="collapseFBSIncidentExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export FBS Incident Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearFBSIncidentFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('fbs-incident.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="type" class="selectpicker form-control" 
                                            required>
                                            <option disabled selected>FBS or All Incidents?</option>
                                            <option value="fbs">FBS Incidents</option>
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
                                        <input type="date" name="date" id="incidentFBSDate"
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
  <span class="text-muted fw-light">All </span> FBS Incidents
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
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createFbsIncident">
                        Add FBS Incident
                    </button>
                    @include('incidents.fbs.create')
                </div>
            @endif
            <table id="fbsIncidentsTable" class="table table-striped data-table-fbs-incidents my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Holder</th>
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

        var analytics = <?php echo $incidentsFbsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        var number = <?php echo $fbsIncidentsNumber;?>;

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options  ={
                title:'Status of FBS Seizures (total '+ number +')',
                is3D:true,
            };

            var chart = new google.visualization.PieChart(
            document.getElementById('incidentsFbsChart'));
            chart.draw(
                data, options
            );

            google.visualization.events.addListener(chart,'select', function() {
                
                var row = chart.getSelection()[0].row;
                var selected_data = data.getValue(row,0);
                
                $.ajax({
                    url: "{{ route('incidentFbsDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#incidentsFbsDetailsModal').modal('toggle');
                        $('#incidentsFbsDetailsTitle').html(selected_data);
                        $('#contentIncidentsFbsTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentIncidentsFbsTable').find('tbody').append('<tr><td>'+item.household+'</td><td>'+item.community+'</td><td>'+item.equipment+'</td><td>'+item.incident+'</td><td>'+item.date+'</td></tr>');
                        }
                    }
                });
            });
        }
    });

    var table;

    function DataTableContent() {

        table = $('.data-table-fbs-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('fbs-incident.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
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
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'fbs_status', name: 'fbs_status'},
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

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDate').val('');
            if ($.fn.DataTable.isDataTable('.data-table-fbs-incidents')) {
                $('.data-table-fbs-incidents').DataTable().destroy();
            }
            DataTableContent();
        });
        
        // Clear Filters for Export
        $('#clearFBSIncidentFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#incidentFBSDate').val(' ');
        });

        // View record details
        $('#fbsIncidentsTable').on('click', '.viewFbsIncident', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // View record photos
        $('#fbsIncidentsTable').on('click', '.updateFbsIncident',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#fbsIncidentsTable').on('click', '.deleteFbsIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteFbsIncident') }}",
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
                                    $('#fbsIncidentsTable').DataTable().draw();
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