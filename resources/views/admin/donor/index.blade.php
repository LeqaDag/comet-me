@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'donors')

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
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_h2o_donor_users" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_grid_donor_users" style="height:450px;">
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
            <p class="card-text">
            @if(Auth::guard('user')->user()->user_type_id == 1)
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
            @endif
            </p>
            <table id="donorTable" class="table table-striped data-table-donors my-2">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Community</th>
                        <th>Service</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1)
                            <th>Options</th>
                        @else 
                            <th></th>
                        @endif
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

        var analyticsWater = <?php echo $donorsWaterData; ?>;
        var analyticsInternet = <?php echo $donorsInternetData; ?>;
        var analyticsWaterUsers = <?php echo $waterUserDonors; ?>;
        var analyticsGridUsers = <?php echo $gridUserDonors; ?>;
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

            var dataH2oDonor = google.visualization.arrayToDataTable(analyticsWaterUsers);
            var optionsH2oDonor = {
                title : 'Households by Donor (H2O )' 
            };

            var dataGridDonor = google.visualization.arrayToDataTable(analyticsGridUsers);
            var optionsGridDonor = {
                title : 'Households by Donor (Grid)' 
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

            var chartH2O = new google.visualization.PieChart(
                document.getElementById('pie_chart_h2o_donor_users'));
            chartH2O.draw(dataH2oDonor, optionsH2oDonor);

            var chartGridUser = new google.visualization.PieChart(
                document.getElementById('pie_chart_grid_donor_users'));
             chartGridUser.draw(dataGridDonor, optionsGridDonor);
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

        var id = 0;

        // Update donor-community
        $('#donorTable').on('click', '.updateDonor',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'community-donor/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // delete energy user
        $('#donorTable').on('click', '.deleteDonor',function() {
            var id = $(this).data('id');

            Swal.fire({ 
                icon: 'warning',
                title: 'Are you sure you want to delete this community-donor?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCommunityDonor') }}",
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
                                    $('#donorTable').DataTable().draw();
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