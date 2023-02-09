@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'communities')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_energy_donor_household" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_water_donor_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_internet_donor_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> donors
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <p class="card-text">
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createDonor">
                        Create New Donor	
                    </button>
                    @include('admin.donor.create')

                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunityDonor">
                        Create New Community Donor	
                    </button>
                    @include('admin.donor.community.create')

                </div>
            </p>
        </div>
        <table id="donorTable" class="table table-striped data-table-donors my-2">
            <thead>
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Community</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Options</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        var analyticsWater = <?php echo $donorsWaterData; ?>;
        var analyticsInternet = <?php echo $donorsInternetData; ?>;

        var analyticsHouseholdEnergy = <?php echo $householdDonorsEnergyData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data1 = google.visualization.arrayToDataTable(analyticsWater);
            var options1 = {
                title : 'Communities by Donor (Water)' 
            };

            var dataInternet = google.visualization.arrayToDataTable(analyticsInternet);
            var optionsInternet = {
                title : 'Communities by Donor (Internet)' 
            };

            var dataHouseholdEnergy = google.visualization.arrayToDataTable(analyticsHouseholdEnergy);
            var optionsHouseholdEnergy = {
                title : 'Households by Donor (Energy)' 
            };

            var chart1 = new google.visualization.PieChart(
                document.getElementById('pie_chart_water_donor_community'));
            chart1.draw(data1, options1);

            var chartInternet = new google.visualization.PieChart(
                document.getElementById('pie_chart_internet_donor_community'));
            chartInternet.draw(dataInternet, optionsInternet);

            var chartHouseholdEnergy = new google.visualization.PieChart(
                document.getElementById('pie_chart_energy_donor_household'));
            chartHouseholdEnergy.draw(dataHouseholdEnergy, optionsHouseholdEnergy);
        }

        // DataTable
        var table = $('.data-table-donors').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('donor.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'donor_name', name: 'donor_name'},
                {data: 'english_name', name: 'english_name'},
                {data: 'service_name', service_name: 'name'},
                { data: 'action' }
            ],
            
        });
    });
</script>

@endsection


