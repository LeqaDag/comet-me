@extends('layouts/layoutMaster')

@section('title', 'displaced households')

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
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseDisplacedHouseholdVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseHouseholdVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseDisplacedHouseholdExport" aria-expanded="false" 
        aria-controls="collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseDisplacedHouseholdVisualData collapseDisplacedHouseholdExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseDisplacedHouseholdVisualData">

    <div class="container">
        <div class="row g-4 mb-4"> 
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Displaced Households by Old Community</h5>
                    </div>
                    <div class="panel-body" >
                        <div class="row">
                            <div id="pie_chart_old_community_household" class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse container mb-4" id="collapseDisplacedHouseholdExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                                Export Displaced Families Report 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearDisplacedHouseholdFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('displaced-household.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="area" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Search Area</option>
                                        <option value="A">Area A</option>
                                        <option value="B">Area B</option>
                                        <option value="C">Area C</option>
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select class="selectpicker form-control" 
                                        data-live-search="true" 
                                        name="sub_region" required>
                                        <option disabled selected>Choose Sub Region...</option>
                                        @foreach($subRegions as $subRegion)
                                        <option value="{{$subRegion->id}}">
                                            {{$subRegion->english_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div> 
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="community" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Search Old Community</option>
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
                                    <input type="date" name="date" id="displacedDate"
                                    class="form-control" title="Displacement Data from"> 
                                </fieldset>
                            </div>
                            <br><br><br>
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

<h4 class="py-3 breadcrumb-wrapper mb-4">
All<span class="text-muted fw-light"> Displaced Families</span> 
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
                        <label class='col-md-12 control-label'>Filter By Old Community</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="filterByOldCommunity">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By New Region</label>
                        <select name="region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterBySubRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($subRegions as $subRegion)
                                <option value="{{$subRegion->id}}">{{$subRegion->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By New Community</label>
                        <select  class="selectpicker form-control" 
                            data-live-search="true" id="filterByNewCommunity">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
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
            @if(Auth::guard('user')->user()->user_type_id != 7 ||
                Auth::guard('user')->user()->user_type_id != 11  )
                <div>
                    <p class="card-text">
                        <div>
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createDisplacedHousehold">
                                Create New Displaced Families	
                            </button>
                        </div>
                        @include('employee.household.displaced.create')
                    </p>
                </div>
            @endif
            <table id="displacedHouseholdsTable" 
                class="table table-striped data-table-displaced-household my-2">
                <thead>
                    <tr>
                        <th class="text-center">Household Name</th>
                        <th class="text-center">Old Community</th>
                        <th class="text-center">New Region</th>
                        <th class="text-center">New Community</th>
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

        table = $('.data-table-displaced-household').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('displaced-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByOldCommunity').val();
                    d.second_filter = $('#filterByNewCommunity').val();
                    d.third_filter = $('#filterBySubRegion').val();
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'old_community', name: 'old_community'},
                {data: 'region', name: 'region'},
                {data: 'new_community', name: 'new_community'},
                {data: 'action' }
            ]
        }); 
    }

    $(function () {

        var analytics = <?php echo $oldCommunityHouseholdsData; ?>;
        
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Displaced Households by Old Community' 
            };

            var chart = new google.charts.Bar(document.getElementById('pie_chart_old_community_household'));
            chart.draw(data, options);
        }

        DataTableContent();
        
        $('#filterBySubRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByNewCommunity').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByOldCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-displaced-household')) {
                $('.data-table-displaced-household').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearDisplacedHouseholdFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#displacedDate').val('');
        });

        // Edit details
        $('#displacedHouseholdsTable').on('click', '.updateDisplacedHousehold',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'displaced-household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url); 
                }
            });
        });

        // View record details
        $('#displacedHouseholdsTable').on('click', '.viewDisplacedHouseholdButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // Delete record
        $('#displacedHouseholdsTable').on('click', '.deleteDisplacedHousehold', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this displaced household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteDisplacedHousehold') }}",
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
                                    $('#displacedHouseholdsTable').DataTable().draw();
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