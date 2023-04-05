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

</script>
@endsection