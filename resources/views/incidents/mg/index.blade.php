@extends('layouts/layoutMaster')

@section('title', 'mg incidents')

@include('layouts.all')

@section('content')

@include('employee.incident_details')

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseMgIncidentVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseMgIncidentVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseMgIncidentExport" aria-expanded="false" 
        aria-controls="collapseMgIncidentExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseMgIncidentVisualData collapseMgIncidentExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 
 
<div class="collapse multi-collapse mb-4" id="collapseMgIncidentVisualData">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div id="incidentsMgChart">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse mb-4" id="collapseMgIncidentExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Export MG Incident System Report
                            <i class='fa-solid fa-file-excel text-info'></i>
                        </h5>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('mg-incident.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="type" class="selectpicker form-control" 
                                            required>
                                            <option disabled selected>MG or All Incidents?</option>
                                            <option value="mg">MG Incidents</option>
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
                                        <input type="date" name="date" 
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
  <span class="text-muted fw-light">All </span> MG Incident Systems
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
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMgIncident">
                        Add MG Incident
                    </button>
                    @include('incidents.mg.create')
                </div>
            @endif
            <table id="mgIncidentsTable" class="table table-striped data-table-mg-incidents my-2">
                <thead>
                    <tr>
                        <th class="text-center">MG System</th>
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

        var analytics = <?php echo $incidentsData; ?>;
        var numberMg = <?php echo $mgIncidentsNumber;?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);


        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options  ={
                title:'Status of Micro-Grids Under Threat of Demolition (total '+ numberMg +')',
                is3D:true,
            };

            var chart = new google.visualization.PieChart(
            document.getElementById('incidentsMgChart'));
            chart.draw(
                data, options
            );


            google.visualization.events.addListener(chart,'select',function() {
                
                var row = chart.getSelection()[0].row;
                var selected_data=data.getValue(row,0);
                
                $.ajax({ 
                    url: "{{ route('incidentDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#incidentsDetailsModal').modal('toggle');
                        $('#incidentsDetailsTitle').html(selected_data);
                        $('#contentIncidentsTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentIncidentsTable').find('tbody').append('<tr><td>'+item.community+'</td><td>'+item.energy+'</td><td>'+item.incident+'</td><td>'+item.date+'</td></tr>');
                        }
                    }
                });
            });
        }
        });

    $(function () {

        var table = $('.data-table-mg-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('mg-incident.index') }}",
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
                {data: 'energy_name', name: 'energy_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'mg_status', name: 'mg_status'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#mgIncidentsTable').on('click', '.viewMgIncident', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // View record photos
        $('#mgIncidentsTable').on('click', '.updateMgIncident',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#mgIncidentsTable').on('click', '.deleteMgIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteMgIncident') }}",
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
                                    $('#mgIncidentsTable').DataTable().draw();
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