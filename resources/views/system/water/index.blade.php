@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-system')

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
                    <div id="waterSystemTypeChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>

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
  <span class="text-muted fw-light">All </span> Water Systems
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createWaterSystem">
                    Create New Water System	
                </button>
            </div>
            <table id="systemWaterTable" class="table table-striped data-table-water-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">Type</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Year</th>
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

        var table = $('.data-table-water-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-system.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'type', name: 'type'},
                {data: 'description', name: 'description'},
                {data: 'year', name: 'year'},
                {data: 'action'},
            ]
        });
    });

    $(function () {

        var analytics = <?php echo $waterSystemTypeData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var chart = new google.charts.Bar(document.getElementById('waterSystemTypeChart'));
            chart.draw(
                data
            );
        } 
    });


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
</script>
@endsection