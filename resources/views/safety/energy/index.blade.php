@extends('layouts/layoutMaster')

@section('title', 'energy safety checks')

@include('layouts.all')

@section('content')

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergySafetyVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergySafetyVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data 
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergySafetyExport" aria-expanded="false" 
        aria-controls="collapseEnergySafetyExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergySafetyVisualData collapseEnergySafetyExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseEnergySafetyVisualData">
    <div class="card mb-4">
        <div class="card-body">
            <h5>Ground Connection Check (Most Recent Check Date) : 
                <span class="text-primary">{{$checkDate[0]->visit_date}}</span>
            </h5>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{$groundConnectedFbs}}</h3>
                                <span class="text-muted">FBS Ground Connected</span>
                                <div class="primary">
                                    <i class="bx bx-plus-circle me-1 bx-lg text-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{$groundNotConnectedFbs}}</h3>
                                <span class="text-muted">FBS Not Ground Connected</span>
                                <div class="primary">
                                    <i class="bx bx-x-circle me-1 bx-lg text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{$groundYes}}</h3>
                                <span class="text-muted">FBS Checked</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-check-circle me-1 bx-lg text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{$notYetChecked}}</h3>
                                <span class="text-muted">FBS Not Yet Checked</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-bolt-circle me-1 bx-lg text-info"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-4 col-sm-4 col-md-4 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{$badResultsNumber}}</h3>
                                <span class="text-muted">Bad Results</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-info-circle me-1 bx-lg text-warning"></i>
                                    </a>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Ground Connected</h5>
                    </div>
                    <div class="card-body">
                        <div id="energySafetyChart"></div>
                    </div>
                </div>  
            </div> 
        </div> 
    </div>  -->
</div> 

<div class="collapse multi-collapse mb-4" id="collapseEnergySafetyExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Energy Safety Check Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearSafteyFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-safety.export') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Region</label>
                                        <select name="region" class="selectpicker form-control" 
                                                data-live-search="true">
                                            <option disabled selected>Search Region</option>
                                            @foreach($regions as $region)
                                            <option value="{{$region->english_name}}">
                                                {{$region->english_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Sub Region</label>
                                        <select name="sub_region" class="selectpicker form-control" 
                                                data-live-search="true">
                                            <option disabled selected>Search Sub Region</option>
                                            @foreach($subRegions as $subRegion)
                                            <option value="{{$subRegion->english_name}}">
                                                {{$subRegion->english_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
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
                                        <label class='col-md-12 control-label'>System Type</label>
                                        <select name="system_type" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Search System Type</option>
                                            @foreach($energySystemTypes as $energySystemType)
                                                <option value="{{$energySystemType->name}}">
                                                    {{$energySystemType->name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Connected Ground</label>
                                        <select name="ground" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Search Ground</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Visit date from</label>
                                        <input type="date" class="form-control" name="date_from"
                                            id="safteyVisitDateFrom">
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Visit date to</label>
                                        <input type="date" class="form-control" name="date_to"
                                            id="safteyVisitDateTo">
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Export</label>
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
  <span class="text-muted fw-light">All </span> Meters Safety Check
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
                <div class="card-header">
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createSafetyEnergyCheck">
                            Create New Energy Safety Check
                        </button>
                        @include('safety.energy.create')
                    </div>
                </div> 
            @endif
            <table id="energySafetyTable" class="table table-striped data-table-energy-safety my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Holder</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Energy System Type</th>
                        <th class="text-center">Ground Connected?</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('safety.energy.details')

<script type="text/javascript">
    $(function () {

        var analytics = <?php echo $energy_users; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var options = {
                title: "",
            };

            var chart = new google.charts.Bar(document.getElementById('energySafetyChart'));
            chart.draw(
                data, 
                options,
            );
        }

        var table = $('.data-table-energy-safety').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-safety.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'ground_connected', name: 'ground_connected'},
                {data: 'action'}
            ]
        });

        // Clear Filters for Export
        $('#clearSafteyFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#safteyVisitDateFrom').val(' ');
            $('#safteyVisitDateTo').val(' ');
        });

        // View record details
        $('#energySafetyTable').on('click', '.updateEnergySafety',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'energy-safety/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energySafetyTable').on('click', '.viewEnergySafety',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-safety/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) { 
                    
                    $('#energyUserModalTitle').html(" ");
                    $('#englishNameUser').html(" ");

                    if(response['household']) {

                        $('#energyUserModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(response['household'].english_name);

                    } else if(response['public']) {

                        $('#energyUserModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(response['public'].english_name);
                    }

                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterCaseUser').html(" ");
                    $('#meterCaseUser').html(response['meter'].meter_case_name_english);

                    $('#systemTypeUser').html(" ");
                    $('#systemTypeUser').html(response['systemType'].name);

                    $('#systemVisitDate').html(" ");
                    $('#systemVisitDate').html(response['energySafety'].visit_date);
                    $('#meterXphase0').html(" ");
                    $('#meterXphase0').html(response['energySafety'].rcd_x_phase0);
                    $('#meterXphase1').html(" ");
                    $('#meterXphase1').html(response['energySafety'].rcd_x_phase1);
                    $('#meterX1phase0').html(" ");
                    $('#meterX1phase0').html(response['energySafety'].rcd_x1_phase0);
                    $('#meterX1phase1').html(" ");
                    $('#meterX1phase1').html(response['energySafety'].rcd_x1_phase1);
                    $('#meterX5phase0').html(" ");
                    $('#meterX5phase0').html(response['energySafety'].rcd_x5_phase0);
                    $('#meterX5phase1').html(" ");
                    $('#meterX5phase1').html(response['energySafety'].rcd_x5_phase1);
                    $('#meterPhLoop').html(" ");
                    $('#meterPhLoop').html(response['energySafety'].ph_loop);
                    $('#meterNLoop').html(" ");
                    $('#meterNLoop').html(response['energySafety'].n_loop);
                    $('#groundConnected').html(" ");
                    $('#groundConnected').html(response['allEnergyMeter'].ground_connected);
                    $('#systemNotesUser').html(" ");
                    $('#systemNotesUser').html(response['energySafety'].notes);
                }
            });
        }); 

        // delete energy safety
        $('#energySafetyTable').on('click', '.deleteEnergySafety',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this check?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergySafety') }}",
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
                                    $('#energySafetyTable').DataTable().draw();
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