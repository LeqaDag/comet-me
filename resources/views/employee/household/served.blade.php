@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'AC Survey households')

@include('layouts.all')

@section('content')

<div class="container mb-4 my-2">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="community_served_households_chart" style="height:300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span>Served Households
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <table id="servedHouseholdsTable" 
                class="table table-striped data-table-served-households my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Region</th>
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

        var analytics = <?php echo $communityServedHouseholdsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Served Households by Region' 
            };

            var chart = new google.visualization.PieChart(document.getElementById('community_served_households_chart'));
            chart.draw(data, options);
        }

        var table = $('.data-table-served-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('served-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'region_name', name: 'region_name'},
            ]
        });
    });
</script>
@endsection