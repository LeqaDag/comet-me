@extends('layouts/layoutMaster')

@section('title', 'water-logframe')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseWaterLogframeExport" aria-expanded="false" 
        aria-controls="collapseWaterLogframeExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseWaterLogframeExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Water System Logframe Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearWaterLogFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('water-log.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Water System</label>
                                        <select name="water_system_id"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search water system...</option>
                                            @foreach($waterSystems as $waterSystem)
                                                <option value="{{$waterSystem->name}}">
                                                    {{$waterSystem->name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Test Year</label>
                                        <select name="year_from" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Filter by Year</option>
                                            @php
                                                $startYear = 2023; // C
                                                $currentYear = date("Y");
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
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
  <span class="text-muted fw-light">All </span> Water Logs
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
                        <label class='col-md-12 control-label'>Water System</label>
                        <select name="water_system_id"
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Search water system<option>
                            @foreach($waterSystems as $waterSystem)
                                <option value="{{$waterSystem->id}}">
                                    {{$waterSystem->name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Test Date</label>
                       <input type="date" class="form-control" id="filterByDate">
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
                        data-bs-toggle="modal" data-bs-target="#createWaterLogframe">
                        Create New Water Logframe
                    </button>
                </div>
                @include('system.water.logframe.create')
            @endif
            <table id="logWaterTable" class="table table-striped data-table-water-log my-2">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Test Date</th>
                        <th class="text-center">Leakage</th>
                        <th class="text-center">Reachability</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('system.water.logframe.show')

<script type="text/javascript">

    $(function () {

        var table = $('.data-table-water-log').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-log.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'test_date', name: 'test_date'},
                {data: 'leakage', name: 'leakage'},
                {data: 'reachability', name: 'reachability'},
                {data: 'action'},
            ]
        });
    });

    // Clear Filters for Export
    $('#clearWaterLogFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // Delete record
    $('#logWaterTable').on('click', '.deleteWaterLog',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this logframe?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWaterLog') }}",
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
                                $('#logWaterTable').DataTable().draw();
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

    // View record details
    $('#logWaterTable').on('click', '.viewWaterLog',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'water-log/' + id,
            type: 'get',
            dataType: 'json', 
            success: function(response) { 

                $('#waterLogModalTitle').html(" ");
                $('#waterLogModalTitle').html(response['waterSystem'].name);
                $('#waterLogName').html(" ");
                $('#waterLogName').html(response['waterSystem'].name);
                $('#waterLogDate').html(" ");
                $('#waterLogDate').html(response['waterSystemLog'].test_date);
                $('#LeakageLog').html(" ");
                $('#LeakageLog').html(response['waterSystemLog'].leakage);
                if(response['waterSystemLog'].leakage == "No") $('#LeakageLog').css('color', 'green');
                else $('#LeakageLog').css('color', 'red');
                $('#waterLogReachability').html(" ");
                $('#waterLogReachability').html(response['waterSystemLog'].reachability);
                if(response['waterSystemLog'].reachability == 90) $('#waterLogReachability').css('color', 'green');
                else $('#waterLogReachability').css('color', 'red');
                $('#freeChlorine').html(" ");
                $('#freeChlorine').html(response['waterSystemLog'].free_chlorine);
                if(response['waterSystemLog'].free_chlorine >= 0.2 && response['waterSystemLog'].free_chlorine <= 0.5)
                    $('#freeChlorine').css('color', 'green');
                else $('#freeChlorine').css('color', 'red');
                $('#logPh').html(" ");
                $('#logPh').html(response['waterSystemLog'].ph);
                if(response['waterSystemLog'].ph >= 6.5 && response['waterSystemLog'].ph <= 8.5)
                    $('#logPh').css('color', 'green');
                else $('#logPh').css('color', 'red');
                $('#logEc').html(" ");
                $('#logEc').html(response['waterSystemLog'].ec);
                if(response['waterSystemLog'].ec <= 2500) $('#logEc').css('color', 'green');
                else $('#logEc').css('color', 'red');
                $('#meterReading').html(" ");
                $('#meterReading').html(response['waterSystemLog'].meter_reading);
                $('#logClusterConsumption').html(" ");
                $('#logClusterConsumption').html(response['waterSystemLog'].daily_avg_cluster_consumption);
                $('#logCapitaConsumption').html(" ");
                $('#logCapitaConsumption').html(response['waterSystemLog'].daily_avg_capita_consumption);

                $('#logNotes').html(" ");
                if(response['waterSystemLog'].notes) $('#logNotes').html(response['waterSystemLog'].notes);
            }
        });
    }); 

    // View record update page
    $('#logWaterTable').on('click', '.updateWaterLog',function() {

        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'water-log/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "new"); 
            }
        });
    });
</script>
@endsection