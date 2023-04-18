@extends('layouts/layoutMaster')

@section('title', 'water incidents')

@include('layouts.all')

@section('content')

@include('system.water.h2o_incidents_details')
<div class="container mb-4">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body" >
                        <div id="incidentsH2oChart" style="height:400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water Incidents
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('incidents.water.show')

<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('water-incident.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community"
                                class="form-control">
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
                                class="form-control">
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
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button class="btn btn-info" type="submit">
                            <i class='fa-solid fa-file-excel'></i>
                            Export Excel
                        </button>
                    </div>
                </div> 
            </form>
        </div>
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createWaterIncident">
                    Add Water Incident
                </button>
                @include('incidents.water.create')
            </div>
            <table id="waterIncidentsTable" class="table table-striped data-table-water-incidents my-2">
                <thead>
                    <tr>
                        <th class="text-center">H2O User</th>
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

        var analytics = <?php echo $h2oIncidents; ?>;
        var numberIncidentsH2o = <?php echo $h2oIncidentsNumber;?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options  ={
                title:'Status of H2O Incidents (total '+ numberIncidentsH2o +')',
                is3D:true,
            };

            var chart = new google.visualization.PieChart(
            document.getElementById('incidentsH2oChart'));
            chart.draw(
                data, options
            );

            google.visualization.events.addListener(chart,'select',function() {
                
                var row = chart.getSelection()[0].row;
                var selected_data=data.getValue(row,0);
                
                $.ajax({
                    url: "{{ route('incidentH2oDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#incidentsH2oDetailsModal').modal('toggle');
                        $('#incidentsH2oDetailsTitle').html(selected_data);
                        $('#contentIncidentsH2oTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentIncidentsH2oTable').find('tbody').append('<tr><td>'+item.household+'</td><td>'+item.community_name+'</td><td>'+item.equipment+'</td><td>'+item.incident+'</td><td>'+item.date+'</td></tr>');
                        }
                    }
                });
            });
        }
    });

    $(function () {

        var table = $('.data-table-water-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-incident.index') }}",
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
                {data: 'household_name', name: 'household_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'incident_status', name: 'incident_status'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#waterIncidentsTable').on('click', '.viewWaterIncident', function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'water-incident/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {
                    $('#waterIncidentModalTitle').html('');
                    $('#waterIncidentModalTitle').html(response['h2oUser'].english_name);
                    $('#waterUser').html('');
                    $('#waterUser').html(response['h2oUser'].english_name);
                    $('#communityName').html('');
                    $('#communityName').html(response['community'].english_name);
                    $('#incidentDate').html('');
                    $('#incidentDate').html(response['incident'].date);
                    $('#waterIncidentStatus').html('')
                    $('#waterIncidentStatus').html(response['waterStatus'].name);
                    $('#incidentYear').html('');
                    $('#incidentYear').html(response['waterIncident'].year);
                    $('#incidentType').html('');
                    $('#incidentType').html(response['incident'].english_name);
                    $('#incidentEquipment').html('');
                    $('#incidentEquipment').html(response['waterIncident'].equipment);
                    $('#incidentNotes').html('');
                    $('#incidentNotes').html(response['waterIncident'].notes);
                }
            });
        });

        // Delete record
        $('#waterIncidentsTable').on('click', '.deleteWaterIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterIncident') }}",
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
                                    $('#waterIncidentsTable').DataTable().draw();
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