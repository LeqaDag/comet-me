@extends('layouts/layoutMaster')

@section('title', 'energy-system')

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
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergySystemVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergySystemVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergySystemExport" aria-expanded="false" 
        aria-controls="collapseEnergySystemExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergySystemVisualData collapseEnergySystemExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseEnergySystemVisualData">
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
</div>

<div class="collapse multi-collapse mb-4" id="collapseEnergySystemExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Energy System Report
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
                        action="{{ route('energy-system.export') }}">
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
                                        <label class='col-md-12 control-label'>Installation Year</label>
                                        <select name="year_from" class="selectpicker form-control" 
                                            data-live-search="true">
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
  <span class="text-muted fw-light">All </span> Energy Systems Design
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
            @if(Auth::guard('user')->user()->user_type_id == 1 || 
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 4 )
                <div>
                    <a type="button" class="btn btn-success" 
                        href="{{url('energy-system', 'create')}}">
                        Create New Energy System	
                    </a>
                    <a type="button" class="btn btn-success" 
                        href="{{url('energy-component', 'create')}}">
                        Create New Energy Components	
                    </a>
                </div>
            @endif
            <table id="systemEnergyTable" class="table table-striped data-table-energy-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Name</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Installtion Year</th>
                        <th class="text-center">Rated Ssolar Power (kW)</th>
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
                {data: 'total_rated_power', name: 'total_rated_power'},
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
        
    // Clear Filters for Export
    $('#clearEnergySystemFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View record update page
    $('#systemEnergyTable').on('click', '.updateEnergySystem',function() {

        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'energy-system/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self"); 
            }
        });
    }); 

    // View record details
    $('#systemEnergyTable').on('click', '.viewEnergySystem',function() {
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
    $('#systemEnergyTable').on('click', '.deleteEnergySystem', function() {
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
                                $('#systemEnergyTable').DataTable().draw();
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