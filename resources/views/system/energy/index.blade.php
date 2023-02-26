@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'energy-system')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>System By Type</h5>
                </div>
                <div class="card-body">
                    <div id="energySystemTypeChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>

@include('employee.incident_details')
<div class="container mb-4">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body" >
                        <div id="incidentsMgChart" style="height:400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('system.energy.fbs_incidents_details')
<div class="container mb-4">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body" >
                        <div id="incidentsFbsChart" style="height:400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Energy Systems Design
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createWaterSystem">
                    Create New Energy System	
                </button>
            </div>
            <table id="systemEnergyTable" class="table table-striped data-table-energy-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Name</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Installtion Year</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Energy Systems
</h4>

<script type="text/javascript">

    $(function () {

        var table = $('.data-table-energy-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-system.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'type', name: 'type'},
                {data: 'installation_year', name: 'installation_year'},
                {data: 'action'},
            ]
        });
    });

    $(function () {

        var analytics = <?php echo $energySystemData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var chart = new google.charts.Bar(document.getElementById('energySystemTypeChart'));
            chart.draw(
                data
            );
        }
    });

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

        var analytics = <?php echo $incidentsFbsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        var number = <?php echo $fbsIncidentsNumber;?>;

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options  ={
                title:'Status of FBS Incidents (total '+ number +')',
                is3D:true,
            };

            var chart = new google.visualization.PieChart(
            document.getElementById('incidentsFbsChart'));
            chart.draw(
                data, options
            );

            google.visualization.events.addListener(chart,'select',function() {
                
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
</script>
@endsection