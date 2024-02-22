@extends('layouts/layoutMaster')

@section('title', 'energy users')

@include('layouts.all')

@section('content')

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergyPublicVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergyPublicVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyPublicExport" aria-expanded="false" 
        aria-controls="collapseEnergyPublicExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergyPublicVisualData collapseEnergyPublicExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p>

<div class="collapse multi-collapse mb-4" id="collapseEnergyPublicVisualData">
    <div class="card mb-4">
        <div class="card-body">
            <h5>Energy Public Facilities</h5>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$schools}}</h2>
                                <span class="text-muted">Schools</span>
                                <div class="primary">
                                    <a target="_blank" type="button">
                                        <i type="solid" class="bx bx-lg bx-buildings text-warning"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$kindergarten}}</h2>
                                <span class="text-muted">Kindergarten</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-lg bx-face text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$clinics}}</h2>
                                <span class="text-muted">Clinics</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-lg bx-clinic text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$mosques}}</h2>
                                <span class="text-muted">Mosques</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-lg bx-arch text-info"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$madafah}}</h2>
                                <span class="text-muted">Madafah</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-lg bx-building text-primary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3mb-4">
                        <div class="col">
                            <div class="card-body text-center">
                                <h2 class="mb-1">{{$center}}</h2>
                                <span class="text-muted">Community Center</span>
                                <div class="">
                                    <a  target="_blank" type="button">
                                    <i class="bx bx-lg bx-store-alt text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-4"> 
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Electricity Public Structures Issues</h5>
                    </div>
                    <div class="panel-body" >
                        <div class="">
                            <div id="energyPublicStructuresChart" >
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

<div class="collapse multi-collapse mb-4" id="collapseEnergyPublicExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Public Structures Meter Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergyPublicFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-public.export') }}">
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
                                        <label class='col-md-12 control-label'>New/MISC/Grid extension</label>
                                        <select name="type" id="selectedWaterSystemType" 
                                            class="selectpicker form-control">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($installationTypes as $installationType)
                                                <option value="{{$installationType->id}}">
                                                    {{$installationType->type}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Installation date from</label>
                                        <input type="date" class="form-control" name="date_from"
                                            id="installationPublicDateFrom">
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Installation date to</label>
                                        <input type="date" class="form-control" name="date_to"
                                            id="installationPublicDateTo">
                                    </fieldset>
                                </div>
                            </div><br>
                            <div class="row">
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
  <span class="text-muted fw-light">All </span> Public Structures Meters
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif
 
@include('users.energy.public.details')

<div class="container mb-4">
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMeterPublic">
                        Create New Public Structure	Meter
                    </button>
                    @include('users.energy.public.create')
                </div>
            @endif
            <table id="energyPublicStructuresTable" 
                class="table table-striped data-table-energy-public-structures my-2">
                <thead>
                    <tr>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Energy System</th>
                        <th class="text-center">Energy System Type</th>
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

        var analyticsPublic = <?php echo $energy_public_structures; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var dataPublic = google.visualization.arrayToDataTable(analyticsPublic);

            var options = {
                title: "",
            };

            var chartPublic = new google.charts.Bar(document.getElementById('energyPublicStructuresChart'));
            chartPublic.draw(
                dataPublic, 
                options,
            );
        }

        var table = $('.data-table-energy-public-structures').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-public.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });

        // Clear Filters for Export
        $('#clearEnergyPublicFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#installationPublicDateFrom').val(' ');
            $('#installationPublicDateTo').val(' ');
        });

        // View record update page
        $('#energyPublicStructuresTable').on('click', '.updateEnergyPublic',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'energy_public/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energyPublicStructuresTable').on('click', '.viewEnergyPublic',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-public/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#energyPublicModalTitle').html(" ");
                    $('#englishNamePublic').html(" ");
                    $('#communityPublic').html(" ");
                    $('#meterActivePublic').html(" ");
                    $('#meterCasePublic').html(" ");
                    $('#systemNamePublic').html(" ");
                    $('#systemTypePublic').html(" ");
                    $('#systemLimitPublic').html(" ");
                    $('#systemDatePublic').html(" ");
                    $('#systemNotesPublic').html(" ");
 
                    $('#energyPublicModalTitle').html(response['public'].english_name);
                    $('#englishNamePublic').html(response['public'].english_name);
                    $('#communityPublic').html(response['community'].english_name);
                    $('#meterActivePublic').html(response['energyPublic'].meter_active);
                    $('#meterCasePublic').html(response['meter'].meter_case_name_english);
                    $('#systemNamePublic').html(response['system'].name);
                    $('#systemTypePublic').html(response['type'].name);
                    $('#systemLimitPublic').html(response['energyPublic'].daily_limit);
                    $('#systemDatePublic').html(response['energyPublic'].installation_date);
                    $('#systemNotesPublic').html(response['energyPublic'].notes);
                    if(response['vendor']) $('#vendorDatePublic').html(response['vendor'].name);
                    $('#installationTypePublic').html(" ");
                    if(response['installationType']) $('#installationTypePublic').html(response['installationType'].type);

                    $('#donorsDetails').html(" ");
                    if(response['energyMeterDonors'] != []) {
                        for (var i = 0; i < response['energyMeterDonors'].length; i++) {
                            if(response['energyMeterDonors'][i].donor_name == "0")  {
                                response['energyMeterDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#donorsDetails").append(
                            '<ul><li>'+ response['energyMeterDonors'][i].donor_name +'</li></ul>');  
                        }
                    }
                }
            });
        });

        // Delete record
        $('#energyPublicStructuresTable').on('click', '.deleteEnergyPublic',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this public structure?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergyPublic') }}",
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
                                    $('#energyPublicStructuresTable').DataTable().draw();
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