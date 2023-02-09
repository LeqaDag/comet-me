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
  <span class="text-muted fw-light">All </span> Energy Systems
</h4>

<script type="text/javascript">

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