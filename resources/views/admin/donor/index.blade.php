@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'donors')

@include('layouts.all')

@section('content')
 
<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseDonorVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseHouseholdVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseDonorExport" aria-expanded="false" 
        aria-controls="collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseDonorVisualData collapseDonorExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse container mb-4" id="collapseDonorVisualData">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_energy_donor_household" style="width:100%;height:250px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_water_donor_community" style="height:250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:15px">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_h2o_donor_users" style="height:250px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_grid_donor_users" style="height:250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top:15px">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_internet_donor_community" style="height:250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse container mb-4" id="collapseDonorExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                                Export Donor Report 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearDonorFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('donor.export') }}">
                    @csrf 
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="community[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select class="selectpicker form-control" 
                                        data-live-search="true" multiple
                                        name="service[]" required>
                                        <option disabled selected>Choose Service Type...</option>
                                        @foreach($services as $service)
                                        <option value="{{$service->id}}">
                                            {{$service->service_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div> 
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select class="selectpicker form-control" 
                                        data-live-search="true" multiple
                                        name="donor[]" required>
                                        <option disabled selected>Choose Donor...</option>
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
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>  
        </div>
    </div> 
</div> 


<h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:25px">
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
                        <label class='col-md-12 control-label'>Filter By Donor</label>
                        <select name="" id="filterByDonor"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Filter By Donor</option>
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
                        <label class='col-md-12 control-label'>Filter By Service</label>
                        <select name="" id="filterByService"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Filter By Service</option>
                            @foreach($services as $service)
                            <option value="{{$service->id}}">
                                {{$service->service_name}}
                            </option>
                            @endforeach
                        </select> 
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
                        Create New Compound/Community Donor	
                    </button>
                    @include('admin.donor.community.create')
                </div>
            @endif
            </p>
            <table id="donorTable" class="table table-striped data-table-donors my-2">
                <thead>
                    <tr>
                        <th>Community / Compound</th>
                        <th>Service</th>
                        <th>Donors</th>
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

        var table;

        function DataTableContent() {
            // DataTable
            table = $('.data-table-donors').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('donor.index') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.community_filter = $('#filterByCommunity').val();
                        d.service_filter = $('#filterByService').val();
                        d.donor_filter = $('#filterByDonor').val();
                    }
                },
                columns: [
                    {data: 'value', name: 'value'},
                    {data: 'service_name', service_name: 'name'},
                    {data: 'donors', name: 'donors'},
                    { data: 'action' }
                ],
                
            });
        }
        
        DataTableContent();

        $('#filterByDonor').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByService').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-donors')) {
                $('.data-table-donors').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearDonorFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
        });

        var id = 0;

        // View record update page
        $('#donorTable').on('click', '.updateDonor', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'donor/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // // Update donor-community
        // $('#donorTable').on('click', '.updateDonor', function() {
        //     var id = $(this).data('id');

        //     // AJAX request
        //     $.ajax({
        //         url: 'getDonorData/' + id,
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(response) {

        //             $('#communityName').html(response.community);

        //             if(response.service == "Electricity") {

        //                 $('#communityService').css("color", "orange");
        //             } else if(response.service == "Water") {

        //                 $('#communityService').css("color", "blue");
        //             } else if(response.service == "Internet") {

        //                 $('#communityService').css("color", "green");
        //             }

        //             $('#communityService').html(response.service);
        //             $('#serviceId').val(response.service_id);
        //             $('#communityDonor').html(response.donor);
        //             $('#donor_id').val(response.donor_id);
        //         }
        //     });

        //     $('#saveDonorCommunityButton').on('click', function() {
                        
        //         donor_id = $('#donor_id').val();
        //         service_id = $('#serviceId').val();

        //         $.ajax({
        //             url: 'donor/edit_community_donor/' + id + '/'+ donor_id + '/' + service_id,
        //             type: 'get',
        //             dataType: 'json',
        //             success: function(response) {
    
        //                 $('#updateDonorCommunityModal').modal('toggle');
        //                 $('#closeDonorCommunityUpdate').click ();
    
        //                 if(response == 1) {
                            
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Community Donor Updated Successfully!',
        //                         showDenyButton: false,
        //                         showCancelButton: false,
        //                         confirmButtonText: 'Okay!'
        //                     }).then((result) => {
    
        //                         $('#donorTable').DataTable().draw();
        //                     });
        //                 }
        //             }
        //         });
        //     });
        // });

        
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