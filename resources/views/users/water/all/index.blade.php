@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-users')

@include('layouts.all')

@section('content')
 
<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseWaterHolderVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseWaterHolderVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseWaterHolderExport" aria-expanded="false" 
        aria-controls="collapseWaterHolderExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseWaterHolderVisualData collapseWaterHolderExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseWaterHolderVisualData">
    <div class="container mb-4">
        <div class="col-lg-12 col-12">
            <div class="row">
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-water fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">H2O Users</span>
                            <h2 class="mb-0">{{$h2oUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-water fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Shared H2O</span>
                            <h2 class="mb-0">{{$h2oSharedUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4"> 
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-droplet fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Integration Users</span>
                            <h2 class="mb-0">{{$gridUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-cloud-rain fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Grid Users</span>
                            <h2 class="mb-0">{{$networkUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-group fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Water beneficiaries</span>
                            <h2 class="mb-0">{{$totalWaterHouseholds->number_of_people}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <i class="bx bx-male fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Male</span>
                            <h2 class="mb-0">{{$totalWaterMale->number_of_male}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-danger">
                                    <i class="bx bx-female fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Female</span>
                            <h2 class="mb-0">{{$totalWaterFemale->number_of_female}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                    <i class="bx bx-male fs-4"></i>
                                    <i class="bx bx-female fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Adults</span>
                            <h2 class="mb-0">{{$totalWaterAdults->number_of_adults}}</h2>
                        </div>
                    </div>
                </div>   
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-dark">
                                    <i class="bx bx-face fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Children</span>
                            <h2 class="mb-0">{{$totalWaterChildren->number_of_children}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card my-2">
            <div class="card-header">
                <h5>Water System Type Chart</h5>
                <div class="container mb-4">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Type</label>
                                <select name="water_type" id="selectedWaterSystemType" 
                                    class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="h2o">Classic H2O System</option>
                                    <option value="grid">Grid Integration</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Status</label>
                                <select name="status" id="selectedWaterStatus" 
                                class="form-control" disabled required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="0">Complete</option>
                                    <option value="1">Not Complete</option>
                                    <option value="2">Delivery</option>
                                    <option value="3">Not Delivery</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="container mb-4" id="chartWaterSystem" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 id="chartWaterSystemTitle"></h5>
                            <div id="waterUserChart"></div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<div class="collapse multi-collapse mb-4" id="collapseWaterHolderExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Water System Holder Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearWaterHolderFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('water-user.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="water_system_id" class="selectpicker form-control" 
                                                data-live-search="true">
                                            <option disabled selected>Search System Type</option>
                                            @foreach($waterSystemTypes as $waterSystemType)
                                            <option value="{{$waterSystemType->id}}">
                                                {{$waterSystemType->type}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset> 
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="complete" class="selectpicker form-control" 
                                                data-live-search="true">
                                            <option disabled selected>Is Complete?</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select> 
                                    </fieldset> 
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="community" class="selectpicker form-control" 
                                                data-live-search="true">
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
                                        <input type="date" name="h2o_installation_date_from" 
                                        class="form-control" title="H2O Installation Data from"
                                            id="installationH2ODateFrom"> 
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" style="margin-top:22px">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <input type="date" name="h2o_installation_date" 
                                        class="form-control" title="H2O Installation Data to"
                                            id="installationH2ODateTo"> 
                                    </fieldset> 
                                </div>
                                <br><br><br>
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
  <span class="text-muted fw-light">All </span> Water System Holders
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
                <div class="col-xl-4 col-lg-4 col-md-4">
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
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Installtion Year</label>
                        <select name="years" class="selectpicker form-control" 
                            data-live-search="true" id="FilterByInstallationYear">
                            <option disabled selected>Choose one...</option>
                            @php
                                $startYear = 2013; // C
                                $currentYear = date("Y");
                            @endphp
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
            
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 11)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createWaterUser">
                        Create New Water System Holder	
                    </button>

                    @include('users.water.create')
                </div>
            @endif
            <table id="waterAllUsersTable" 
                class="table table-striped data-table-water-all-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">Water Holder</th>
                        <th class="text-center">Main Holder</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('users.water.details')

<script type="text/javascript">

    $(document).on('change', '#selectedWaterSystemType', function () {
        water_type = $(this).val();

        if(water_type == "h2o") {
            $("#chartWaterSystem").css("visibility", "visible");
            $("#chartWaterSystem").css('display', 'block');
            $("#chartWaterSystemTitle").html("Classic H2O System");

            var analytics = <?php echo $h2oChartStatus; ?>;

            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(analytics);
                
                var chart = new google.charts.Bar(document.getElementById('waterUserChart'));
                chart.draw(
                    data
                );

                google.visualization.events.addListener(chart,'select',function() {
                    var row = chart.getSelection()[0].row;
                    var selected_data=data.getValue(row,0);
                   
                    $.ajax({
                    url: "{{ route('waterChartDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#h2oDetailsModal').modal('toggle');
                        $('#h2oDetailsTitle').html(selected_data);
                        $('#contentH2oTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentH2oTable').find('tbody').append('<tr><td>'+item.english_name+'</td><td>'+item.community_name+'</td><td>'+item.number_of_h20+'</td><td>'+ item.number_of_bsf +'</td></tr>');
                        }
                    }
                    });
                });
            }
        }
        if(water_type == "grid") {
            $('#selectedWaterStatus').prop('disabled', false);
            
            $(document).on('change', '#selectedWaterStatus', function () {
                water_status = $(this).val();

                $.ajax({
                    url: "{{ route('chartWater') }}",
                    type: 'get',
                    data: {
                        water_type: water_type,
                        water_status:water_status
                    },
                    success: function(data) {
           
                        $("#chartWaterSystem").css("visibility", "visible");
                        $("#chartWaterSystem").css('display', 'block');
                        $("#chartWaterSystemTitle").html("Grid Integration System");
                        var analyticsGrid = data;

                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var dataGrid = google.visualization.arrayToDataTable(analyticsGrid);

                            var chartGrid = new google.charts.Bar(
                                document.getElementById('waterUserChart'));
                            chartGrid.draw(
                                dataGrid
                            );
                        }
                    }
                });
            });
        }
    });

    var table ;
    function DataTableContent() {

        // DataTable
        table = $('.data-table-water-all-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-water.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByCommunity').val();
                    d.second_filter = $('#FilterByInstallationYear').val();
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'icon'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'}
            ],
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); // Reload DataTable when the dropdown value changes
        });

        $('#FilterByInstallationYear').on('change', function() {
            table.ajax.reload(); // Reload DataTable when the dropdown value changes
        });
    }); 

    // Clear Filter
    $('#clearFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        if ($.fn.DataTable.isDataTable('.data-table-water-all-users')) {
            $('.data-table-water-all-users').DataTable().destroy();
        }
        DataTableContent();
    });

    // Clear Filters for Export
    $('#clearWaterHolderFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#installationH2ODateFrom').val(' ');
        $('#installationH2ODateTo').val(' ');
    });

    // Update record
    $('#waterAllUsersTable').on('click', '.updateWaterUser',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'all-water/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self");
            }
        });
    });

    // View record details
    $('#waterAllUsersTable').on('click', '.viewWaterUser', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ id ;
        window.open(url); 
    });

    // Delete record
    $('#waterAllUsersTable').on('click', '.deleteWaterUser',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this user?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWaterUser') }}",
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
                                $('#waterAllUsersTable').DataTable().draw();
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
    
</script>
@endsection