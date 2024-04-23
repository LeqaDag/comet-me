@extends('layouts/layoutMaster')

@section('title', 'energy-cost')

@include('layouts.all')

@section('content')
<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<p>
    <!-- <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergySystemVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergySystemVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a> -->
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergySystemCostExport" aria-expanded="false" 
        aria-controls="collapseEnergySystemCostExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <!-- <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergySystemVisualData collapseEnergySystemCostExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button> -->
</p> 

<!-- <div class="collapse multi-collapse mb-4" id="collapseEnergySystemVisualData">
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
</div> -->

<div class="collapse multi-collapse mb-4" id="collapseEnergySystemCostExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Energy System Cost Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergySystemFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-cost.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id"
                                            class="selectpicker form-control" data-live-search="true">
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
                                        <label class='col-md-12 control-label'>System Type</label>
                                        <select name="energy_type_id"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search System Type</option>
                                            @foreach($energyTypes as $energyType)
                                                <option value="{{$energyType->id}}">
                                                    {{$energyType->name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset> 
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Cycle Year</label>
                                        <select name="energy_cycle_id"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Cycle Year</option>
                                            @foreach($energyCycles as $energyCycle)
                                                <option value="{{$energyCycle->id}}">
                                                    {{$energyCycle->name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Download Excel</label>
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
  <span class="text-muted fw-light">All </span> Energy Systems Costs
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
                        <label class='col-md-12 control-label'>Filter By System Type</label>
                        <select name="energy_type_id" id="filterByType"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Search System Type</option>
                            @foreach($energyTypes as $energyType)
                                <option value="{{$energyType->id}}">
                                    {{$energyType->name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Installation Year</label>
                        <select name="year_from" class="selectpicker form-control" 
                            data-live-search="true" id="filterByYear">
                            <option disabled selected>Filter by Year</option>
                            @php
                                $startYear = 2010; // C
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
                Auth::guard('user')->user()->user_type_id == 2 )
                <div>
                    <a type="button" class="btn btn-success" 
                        href="{{url('energy-cost', 'create')}}">
                        Create New Energy Cost	
                    </a>
                </div>
            @endif
            <table id="costEnergyTable" class="table table-striped data-table-energy-cost my-2">
                <thead>
                    <tr>
                        <th>Energy Name</th>
                        <th>Type</th>
                        <th>Installtion Year</th>
                        <th>Cost <i class="menu-icon tf-icons bx bx-shekel"></i></th>
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

    var table;

    function DataTableContent() {
        table = $('.data-table-energy-cost').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-cost.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.type_filter = $('#filterByType').val();
                    d.year_filter = $('#filterByYear').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'type', name: 'type'},
                {data: 'installation_year', name: 'installation_year'},
                {data: 'total_costs', name: 'total_costs'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByYear').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByType').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-energy-cost')) {
                $('.data-table-energy-cost').DataTable().destroy();
            }
            DataTableContent();
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
        
    // Clear Filters for Export
    $('#clearEnergySystemFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View record update page
    $('#costEnergyTable').on('click', '.updateEnergyCost',function() {

        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'energy-cost/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self"); 
            }
        });
    }); 

    // View record details
    $('#costEnergyTable').on('click', '.viewEnergySystem',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id;

        // AJAX request
        $.ajax({
            url: 'energy-system/' + id + '/showPage',
            type: 'get',
            dataType: 'json',
            success: function(response) {

                window.open(url, "_self"); 
            }
        });
    });

    // Delete record
    $('#costEnergyTable').on('click', '.deleteEnergySystem', function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this energy system?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystem') }}",
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
                                $('#costEnergyTable').DataTable().draw();
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