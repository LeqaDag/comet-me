@extends('layouts/layoutMaster')
<link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
<link href='https://cdn.boxicons.com/3.0.3/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>

@section('title', 'In Progress Households')

@include('layouts.all')

<style>
    .nav-tabs .nav-link .bx {
        color: #007bff;
        font-size: 1.2em;
        vertical-align: middle;
        margin-left: 4px;
    }

    .nav-tabs .nav-link.active .bx {
        color: #28a745;
    }

    .nav-tabs .nav-link .bx-poll {
        color: #007bff;
    }

    .nav-tabs .nav-link .bx-check-square {
        color: #17a2b8;
    }

    .nav-tabs .nav-link .bx-message-question-mark {
        color: #ffc107;
    }

    .nav-tabs .nav-link .bx-message-check {
        color: #20c997;
    }

    .nav-tabs .nav-link .bx-checks {
        color: #6f42c1;
    }

    .nav-tabs .nav-link .bx-server {
        color: #fd7e14;
    }

    .nav-tabs .nav-link .bx-x {
        color: #dc3545;
    }

    .nav-tabs .nav-link.active .bx {
        filter: drop-shadow(0 0 2px #28a745);
    }
</style>

@section('content')

    @include('employee.household.details')
    <p>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseEnergyRequestExport"
            aria-expanded="false" aria-controls="collapseEnergyRequestExport">
            <i class="menu-icon tf-icons bx bx-export"></i>
            Export Data
        </button>
    </p>

    <div class="collapse multi-collapse container mb-4" id="collapseEnergyRequestExport">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xl-10 col-lg-10 col-md-10">
                                    <h5>
                                        Export Report
                                        <i class='fa-solid fa-file-excel text-info'></i>
                                    </h5>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2">
                                    <fieldset class="form-group">
                                        <button class="" id="clearInProgressFiltersButton">
                                            <i class='fa-solid fa-eraser'></i>
                                            Clear Filters
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <form id="exportEnergyForm" method="POST" enctype='multipart/form-data' action="{{ route('energy-request.export') }}">
                            @csrf
                            <input type="hidden" name="energy_project" id="energyProjectFlag" value="0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group" >
                                            <label class='col-md-12 control-label'>Exportation Type</label>
                                            <select name="export_type" required class="selectpicker form-control"
                                                data-live-search="true">
                                                <option value="" disabled selected>Choose one...</option>
                                                <option value="initial">Initial Survey</option>
                                                <option value="ac">AC Survey Completed</option>
                                                <option value="requested">Requested</option>
                                                <option value="confirmed">Confirmed</option>
                                                <option value="ac_completed">AC Completed</option>
                                                <option value="dc">Active/No meter</option>
                                                <option value="served">Served</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <label class='col-md-12 control-label'>Community</label>
                                            <select name="community_id" class="selectpicker form-control"
                                                data-live-search="true">
                                                <option disabled selected>Choose one...</option>
                                                @foreach($communities as $community)
                                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <label class='col-md-12 control-label'>Status</label>
                                            <select name="status" class="selectpicker form-control" data-live-search="true">
                                                <option disabled selected>Choose one...</option>
                                                <option value="served">Served</option>
                                                <option value="service_requested">Service requested</option>
                                                <option value="displaced">Displaced</option>
                                                <option value="requested">Requested</option>
                                                <option value="confirmed">Confirmed</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <fieldset class="form-group">
                                            <label class='col-md-12 control-label'>System Type (if shared)</label>
                                            <select name="energy_system_type_id" class="selectpicker form-control"
                                                data-live-search="true">
                                                <option disabled selected>Choose one...</option>
                                                @foreach($energySystemTypes as $energySystemType)
                                                    <option value="{{$energySystemType->id}}">{{$energySystemType->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div id="exportRequestedDateField" class="col-xl-3 col-lg-3 col-md-3" style="display:none">
                                        <fieldset class="form-group">
                                            <label class='col-md-12 control-label'>Requested Date</label>
                                            <input type="date" name="request_date" id="filterByRequestedDateExport" class="form-control" />
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
    <div class="container mb-4 my-2">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Households — In Progress</h5>
                    </div>
                    <div class="panel-body">
                        <p class="mb-0">Select a tab to view households in that status.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Households </span>In Progress
    </h4>

    <script type="text/javascript">
        $(function () {
            function updateExportFields() {
                var v = $('select[name="export_type"]').val();
                if (!v) v = '';
                v = v.toString().toLowerCase();

                if (v === 'initial' || v === 'ac' || v === 'ac_completed') {
                    $('select[name="status"]').val('');
                    $('select[name="status"]').prop('disabled', true);
                    $('select[name="energy_system_type_id"]').val('');
                    $('select[name="energy_system_type_id"]').prop('disabled', true);
                } else {
                    $('select[name="status"]').prop('disabled', false);
                    $('select[name="energy_system_type_id"]').prop('disabled', false);
                }

                // Show requested date input only when export_type is 'requested'
                if (v === 'requested') {
                    $('#exportRequestedDateField').show();
                } else {
                    $('#exportRequestedDateField').hide();
                    $('#filterByRequestedDateExport').val('');
                }

                if ($('.selectpicker').length && typeof $('.selectpicker').selectpicker === 'function') {
                    $('.selectpicker').selectpicker('refresh');
                }
            }

            // Init on load
            updateExportFields();

            // Watch changes
            $(document).on('change', 'select[name="export_type"]', function () {
                updateExportFields();
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            // Clear export filters button inside the Export Report collapse
            $(document).on('click', '#clearInProgressFiltersButton', function(e) {
                e.preventDefault();

                var form = document.getElementById('exportEnergyForm');
                if (form) form.reset();

                $('#energyProjectFlag').val('0');

                $('#filterByRequestedDateExport').val('');

                $('#exportEnergyForm').find('select').each(function() {
                    this.selectedIndex = 0;
                });
                if ($('.selectpicker').length && typeof $('.selectpicker').selectpicker === 'function') {
                    $('.selectpicker').selectpicker('refresh');
                }


            });
        });
    </script>

        <script type="text/javascript">
            $(function () {
                // If the export form is submitted with no choices, mark energy_project flag
                $('#exportEnergyForm').on('submit', function (e) {
                    var exportType = $('select[name="export_type"]').val();
                    var community = $('select[name="community_id"]').val();
                    var status = $('select[name="status"]').val();
                    var systemType = $('select[name="energy_system_type_id"]').val();
                    var requestDate = $('input[name="request_date"]').val();
                    var cycleYear = $('select[name="energy_cycle_id"]').val();

                    function isEmpty(v) { return (v === undefined || v === null || v === '' ); }

                    var noneSelected = isEmpty(exportType) && isEmpty(community) && isEmpty(status) && isEmpty(systemType) && isEmpty(requestDate) && isEmpty(cycleYear);
                    var onlyCycleSelected = !isEmpty(cycleYear) && isEmpty(exportType) && isEmpty(community) && isEmpty(status) && isEmpty(systemType) && isEmpty(requestDate);

                    // If nothing selected OR only cycle year selected -> request Energy Project export
                    if (noneSelected || onlyCycleSelected) {
                        $('#energyProjectFlag').val('1');
                    } else {
                        $('#energyProjectFlag').val('0');
                    }
                });
            });
        </script>

    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label">Filter By Community</label>
                            <select id="filterByCommunity" class="selectpicker form-control">
                                <option value="">All Communities</option>
                                @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label">Filter By Region</label>
                            <select id="filterByRegion" class="selectpicker form-control">
                                <option value="">All Regions</option>
                                @if(isset($regions))
                                    @foreach($regions as $region)
                                        <option value="{{$region->id}}">{{$region->english_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <label class="form-label">Filter By System Type</label>
                            <select id="filterBySystemType" class="selectpicker form-control">
                                <option value="">All Types</option>
                                @if(isset($energySystemTypes))
                                    @foreach($energySystemTypes as $energySystemType)
                                        <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div id="requestedDateFilter" class="col-12 col-sm-6 col-md-4 col-lg-3" style="display:none">
                            <label class="form-label">Filter By Requested Date</label>
                            <input type="date" id="filterByRequestedDate" class="form-control" />
                        </div>

                        <div id="requestedStatusFilter" class="col-12 col-sm-6 col-md-4 col-lg-3" style="display:none">
                            <label class="form-label">Filter By Requested Status</label>
                            <select id="filterByStatus" class="selectpicker form-control" disabled>
                                <option value="">All Statuses</option>
                                <option value="served">Served</option>
                                <option value="service_requested">Service requested</option>
                                <option value="displaced">Displaced</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <button class="btn btn-dark" id="clearFiltersButton">Clear Filters</button>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs" role="tablist" id="inProgressTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-initial" role="tab"> Initial Survey <i
                                class='bxr  bx-poll'></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-ac" role="tab">AC Survey Completed <i
                                class='bxr  bx-check-square'></i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-requested" role="tab">Requested <i
                                class='bxr  bx-message-question-mark'></i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-confirmed" role="tab">Confirmed <i
                                class='bxr  bx-message-check'></i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-ac-completed" role="tab">AC Completed <i
                                class='bxr  bx-checks'></i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-dc" role="tab">Active/No meter <i
                                class='bxr  bx-x'></i> </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-served" role="tab">Served <i
                                class='bxr  bx-server'></i> </a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="tab-initial" role="tabpanel">
                        @if(Auth::guard('user')->user()->user_type_id == 1 || Auth::guard('user')->user()->user_type_id == 2)
                            <div class="mb-2">
                                <a type="button" class="btn btn-success" href="{{ url('household', 'create') }}">
                                    Create New Household
                                </a>
                            </div>
                        @endif
                        <h5 class="panel-header">Households — Initial Survey </h5>
                        <table id="initialHouseholdsTable" class="table table-striped data-table-initial-households my-2">
                            <thead>
                                <tr>
                                    <th class="text-center">English Name</th>
                                    <th class="text-center">Arabic Name</th>
                                    <th class="text-center">Community</th>
                                    <th class="text-center">Region</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tab-ac" role="tabpanel">
                        @if(Auth::guard('user')->user()->user_type_id == 1 || Auth::guard('user')->user()->user_type_id == 2)
                            <div class="mb-2">
                                <a type="button" class="btn btn-success" href="{{ url('ac-household', 'create') }}">
                                    Create New AC-Survey Household
                                </a>
                            </div>
                        @endif
                        <h5 class="panel-header">Households — Ac-Survey Completed </h5>
                        <table id="acHouseholdsTable" class="table table-striped data-table-ac-households my-2">
                            <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Community</th>
                                    <th>Region</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tab-requested" role="tabpanel">
                        <div class="card-header">
                            @if(
                                    Auth::guard('user')->user()->user_type_id == 1 ||
                                    Auth::guard('user')->user()->user_type_id == 2 ||
                                    Auth::guard('user')->user()->user_type_id == 3 ||
                                    Auth::guard('user')->user()->user_type_id == 4
                                )

                                <a type="button" class="btn btn-success" href="{{url('energy-request', 'create')}}">
                                    Create New Request System
                                </a>

                            @endif
                        </div>
                        <h5 class="panel-header">Households — Requested </h5>
                        <table id="requestedHouseholdsTable"
                            class="table table-striped data-table-requested-households my-2">
                            <thead>
                                <tr>
                                    <th class="text-center">Requested Household</th>
                                    <th class="text-center">Community</th>
                                    <th class="text-center">Request Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Shared</th>
                                    <th class="text-center">Referred By</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:40px">
                            <span class="text-muted fw-light">All </span> Deleted Requested Households
                        </h4>

                        @if(session()->has('message_info'))
                            <div class="row">
                                <div class="alert alert-success">
                                    {{ session()->get('message_info') }}
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
                                                    data-live-search="true" id="filterByCommunityDeleted">
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
                                                <select name="energy_system_type_id" class="selectpicker form-control"
                                                    data-live-search="true" id="filterBySystemTypeDeleted">
                                                    <option disabled selected>Choose one...</option>
                                                    @foreach($energySystemTypes as $energySystemType)
                                                        <option value="{{$energySystemType->id}}">{{$energySystemType->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <fieldset class="form-group">
                                                <label class='col-md-12 control-label'>Clear All Filters</label>
                                                <button class="btn btn-dark" id="clearFiltersButtonDeleted">
                                                    <i class='fa-solid fa-eraser'></i>
                                                    Clear Filters
                                                </button>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="panel-header">Households — Deleted </h5>
                                    <table id="energyDeletedRequestTable"
                                        class="table table-striped data-table-energy-delete-request my-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Deleted Household</th>
                                                <th class="text-center">Community</th>
                                                <th class="text-center">Reason</th>
                                                <th class="text-center">Deleted By</th>
                                                <th class="text-center">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-confirmed" role="tabpanel">
                        <h5 class="panel-header">Households — Confirmed</h5>

                        <table id="confirmedHouseholdsTable"
                            class="table table-striped data-table-confirmed-households my-2">
                            <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Community</th>
                                    <th>Region</th>
                                    <th>Confirmation Notes</th>
                                    <th>Referred By</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tab-ac-completed" role="tabpanel">
                        <div>
                            @if(
                                    Auth::guard('user')->user()->user_type_id == 1 ||
                                    Auth::guard('user')->user()->user_type_id == 2 ||
                                    Auth::guard('user')->user()->user_type_id == 3 ||
                                    Auth::guard('user')->user()->user_type_id == 4 ||
                                    Auth::guard('user')->user()->user_type_id == 12
                                )
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 mb-3">
                                        <a type="button" class="btn btn-success" href="{{url('progress-household', 'create')}}">
                                            Create New Elc. / In Progress Household
                                        </a>
                                    </div>

                                </div>
                            @endif
                        </div>
                        <h5 class="panel-header">Households — AC Completed </h5>
                        <table id="acCompletedHouseholdsTable"
                            class="table table-striped data-table-ac-completed-households my-2">
                            <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Community</th>
                                    <th>Region</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tab-dc" role="tabpanel">
                        <h5 class="panel-header">Households — Active/No Meter </h5>

                        <table id="dcHouseholdsTable" class="table table-striped data-table-dc-households my-2">
                            <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Community</th>
                                    <th>Region</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tab-served" role="tabpanel">
                        <h5 class="panel-header">Households — Served </h5>
                        <table id="servedHouseholdsTable" class="table table-striped data-table-served-households my-2">
                            <thead>
                                <tr>
                                    <th>English Name</th>
                                    <th>Arabic Name</th>
                                    <th>Community</th>
                                    <th>Region</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <script type="text/javascript">
                    $(function () {
                        // keep track of initialized tables
                        var tables = {};

                        function initInitialTable() {
                            if (tables.init) return;
                            tables.init = $('.data-table-initial-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('initial-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initAcTable() {
                            if (tables.ac) return;
                            tables.ac = $('.data-table-ac-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('ac-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initRequestedTable() {
                            if (tables.requested) return;
                            tables.requested = $('.data-table-requested-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('requested-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                        d.date_filter = $('#filterByRequestedDate').val();
                                        d.household_status = $('#filterByStatus').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'created_at', name: 'created_at' },
                                    { data: 'status', name: 'status' },
                                    { data: 'type', name: 'type' },
                                    { data: 'referred_by', name: 'referred_by' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initConfirmedTable() {
                            if (tables.confirmed) return;
                            tables.confirmed = $('.data-table-confirmed-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('misc-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'confirmation_notes', name: 'confirmation_notes', render: function(data, type, row) {
                                        if (!data) return '';
                                        // Shortness the notes if too long
                                        var text = data.toString();
                                        if (text.length > 50) {
                                            return text.substring(0, 47) + '...';
                                        }
                                        return text;
                                    } },
                                    { data: 'referred_by', name: 'referred_by' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initAcCompletedTable() {
                            if (tables.accompleted) return;
                            tables.accompleted = $('.data-table-ac-completed-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('progress-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initServedTable() {
                            if (tables.served) return;
                            tables.served = $('.data-table-served-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('served-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        function initDcTable() {
                            if (tables.dc) return;
                            tables.dc = $('.data-table-dc-households').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('dc-household.index') }}",
                                    data: function (d) {
                                        d.search = (d.search && d.search.value) ? d.search.value : '';
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'arabic_name', name: 'arabic_name' },
                                    { data: 'name', name: 'name' },
                                    { data: 'region_name', name: 'region_name' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        initInitialTable();

                        // On tab shown, lazy-init the target table
                        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                            var target = $(e.target).attr('href');
                            if (target == '#tab-initial') initInitialTable();
                            if (target == '#tab-ac') initAcTable();
                            if (target == '#tab-requested') {
                                initRequestedTable();
                            }
                            if (target == '#tab-confirmed') initConfirmedTable();
                            if (target == '#tab-ac-completed') initAcCompletedTable();
                            if (target == '#tab-dc') initDcTable();
                            if (target == '#tab-served') initServedTable();

                            // Show the requested filters only when Requested tab is active
                            if (target == '#tab-requested') {
                                $('#requestedStatusFilter').show();
                                $('#requestedDateFilter').show();
                                $('#filterByStatus').prop('disabled', false);
                            } else {
                                $('#requestedStatusFilter').hide();
                                $('#requestedDateFilter').hide();
                                $('#filterByStatus').prop('disabled', true);
                            }

                            if ($('.selectpicker').length && typeof $('.selectpicker').selectpicker === 'function') {
                                $('.selectpicker').selectpicker('refresh');
                            }
                        });

                        // Reload initialized tables when any filter changes
                        function reloadInitializedTables() {
                            if (tables.init) tables.init.ajax.reload();
                            if (tables.ac) tables.ac.ajax.reload();
                            if (tables.requested) tables.requested.ajax.reload();
                            if (tables.confirmed) tables.confirmed.ajax.reload();
                            if (tables.accompleted) tables.accompleted.ajax.reload();
                            if (tables.dc) tables.dc.ajax.reload();
                            if (tables.served) tables.served.ajax.reload();
                        }

                        $('#filterByCommunity, #filterBySystemType, #filterByStatus, #filterByRequestedDate').on('change', function () {
                            if (tables.init) tables.init.ajax.reload();
                            if (tables.ac) tables.ac.ajax.reload();
                            if (tables.requested) tables.requested.ajax.reload();
                            if (tables.confirmed) tables.confirmed.ajax.reload();
                            if (tables.accompleted) tables.accompleted.ajax.reload();
                            if (tables.dc) tables.dc.ajax.reload();
                            if (tables.served) tables.served.ajax.reload();
                        });

                        // Region filter: listen to both native change and bootstrap-select change events
                        $(document).on('change', '#filterByRegion', function () {
                            reloadInitializedTables();
                        });
                        $(document).on('changed.bs.select', '#filterByRegion', function () {
                            reloadInitializedTables();
                        });

                        // Clear filters
                        $(document).on('click', '#clearFiltersButton', function () {
                            $('#filterByCommunity').prop('selectedIndex', 0);
                            $('#filterByRegion').prop('selectedIndex', 0);
                            $('#filterBySystemType').prop('selectedIndex', 0);
                            $('#filterByStatus').prop('selectedIndex', 0);
                            $('#filterByRequestedDate').val('');
                            $('.selectpicker').selectpicker('refresh');
                            if (tables.init) tables.init.ajax.reload();
                            if (tables.ac) tables.ac.ajax.reload();
                            if (tables.requested) tables.requested.ajax.reload();
                            if (tables.confirmed) tables.confirmed.ajax.reload();
                            if (tables.accompleted) tables.accompleted.ajax.reload();
                            if (tables.dc) tables.dc.ajax.reload();
                            if (tables.served) tables.served.ajax.reload();
                        });
                    });
                </script>
                <script type="text/javascript">
                    $(function () {
                        // Deleted requested households DataTable and handlers
                        var tableDeleted;

                        function initDeletedRequestedTable() {
                            if (tableDeleted) return;
                            tableDeleted = $('.data-table-energy-delete-request').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('energy-delete-request.index') }}",
                                    data: function (d) {
                                        d.search = $('input[type="search"]').val();
                                        d.community_deleted_filter = $('#filterByCommunityDeleted').val();
                                        d.system_type_deleted_filter = $('#filterBySystemTypeDeleted').val();
                                        d.community_filter = $('#filterByCommunity').val();
                                        d.region_filter = $('#filterByRegion').val();
                                        d.system_type_filter = $('#filterBySystemType').val();
                                    }
                                },
                                columns: [
                                    { data: 'english_name', name: 'english_name' },
                                    { data: 'community_name', name: 'community_name' },
                                    { data: 'reason', name: 'reason' },
                                    { data: 'referred_by', name: 'referred_by' },
                                    { data: 'action' }
                                ]
                            });
                        }

                        // initialize when the Requested tab is shown
                        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                            var target = $(e.target).attr('href');
                            if (target == '#tab-requested') initDeletedRequestedTable();
                        });

                        // filter change handlers
                        $(document).on('change', '#filterBySystemTypeDeleted', function () {
                            if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                                $('.data-table-energy-delete-request').DataTable().ajax.reload();
                            }
                        });
                        $(document).on('change', '#filterByCommunityDeleted', function () {
                            if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                                $('.data-table-energy-delete-request').DataTable().ajax.reload();
                            }
                        });

                        // Clear Filter
                        $(document).on('click', '#clearFiltersButtonDeleted', function () {
                            $('.selectpicker').prop('selectedIndex', 0);
                            $('.selectpicker').selectpicker('refresh');
                            if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                                $('.data-table-energy-delete-request').DataTable().destroy();
                            }
                            tableDeleted = null;
                        });

                        // Return record
                        $(document).on('click', '#energyDeletedRequestTable .returnEnergyDeletedRequest', function () {
                            var id = $(this).data('id');

                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure you want to return this deleted household to the requested list?',
                                showDenyButton: true,
                                confirmButtonText: 'Confirm',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "{{ route('returnEnergyDeletedRequest') }}",
                                        type: 'get',
                                        data: { id: id },
                                        success: function (response) {
                                            if (response.success == 1) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: response.msg,
                                                    showDenyButton: false,
                                                    showCancelButton: false,
                                                    confirmButtonText: 'Okay!'
                                                }).then((result) => {
                                                    if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                                                        $('.data-table-energy-delete-request').DataTable().draw();
                                                    }
                                                });
                                            } else {
                                                alert("Invalid ID.");
                                            }
                                        }
                                    });
                                }
                            });
                        });

                        $(document).on('click', '#energyDeletedRequestTable .deleteEnergyDeletedRequest', function () {
                            var id = $(this).data('id');

                            Swal.fire({
                                icon: 'warning',
                                title: 'Are you sure you want to remove this deleted household from the list?',
                                showDenyButton: true,
                                confirmButtonText: 'Confirm',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "{{ route('deleteEnergyDeletedRequest') }}",
                                        type: 'get',
                                        data: { id: id },
                                        success: function (response) {
                                            if (response.success == 1) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: response.msg,
                                                    showDenyButton: false,
                                                    showCancelButton: false,
                                                    confirmButtonText: 'Okay!'
                                                }).then((result) => {
                                                    if ($.fn.DataTable.isDataTable('.data-table-energy-delete-request')) {
                                                        $('.data-table-energy-delete-request').DataTable().draw();
                                                    }
                                                });
                                            } else {
                                                alert("Invalid ID.");
                                            }
                                        }
                                    });
                                }
                            });
                        });
                    });
                </script>
                @include('employee.household._in_progress_handlers')
            </div>
        </div>
    </div>

    <script type="text/javascript">
        

        $(function () {
            var table;
            function DataTableContent() {
                table = $('.data-table-energy-request').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('energy-request.index') }}",
                        data: function (d) {
                            d.search = $('input[type="search"]').val();
                            d.community_filter = $('#filterByCommunity').val();
                            d.system_type_filter = $('#filterBySystemType').val();
                            d.date_filter = $('#filterByRequestedDate').val();
                            d.household_status = $('#filterByStatus').val();
                        }
                    },
                    columns: [
                        { data: 'english_name', name: 'english_name' },
                        { data: 'community_name', name: 'community_name' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status' },
                        { data: 'type', name: 'type' },
                        { data: 'referred_by', name: 'referred_by' },
                        { data: 'action' }
                    ]
                });
            }

            $(function () {
                DataTableContent();

                $('#filterBySystemType').on('change', function () {
                    table.ajax.reload();
                });
                $('#filterByRequestedDate').on('change', function () {
                    table.ajax.reload();
                });
                $('#filterByCommunity').on('change', function () {
                    table.ajax.reload();
                });
                $('#filterByStatus').on('change', function () {
                    table.ajax.reload();
                });

                // Clear Filter
                $('#clearFiltersButton').on('click', function () {

                    $('.selectpicker').prop('selectedIndex', 0);
                    $('.selectpicker').selectpicker('refresh');
                    $('#filterByRequestedDate').val('');
                    if ($.fn.DataTable.isDataTable('.data-table-energy-request')) {
                        $('.data-table-energy-request').DataTable().destroy();
                    }
                    DataTableContent();
                });
            });

            // Clear Filters for Export
            $('#clearRequestedFiltersButton').on('click', function () {

                $('.selectpicker').prop('selectedIndex', 0);
                $('.selectpicker').selectpicker('refresh');
                $('#filterByRequestedDateExport').val(' ');
            });

            // View record details
            $('#energyRequestTable').on('click', '.viewEnergyRequest', function () {

                var id = $(this).data('id');
                window.open('/household?id=' + id, '_blank');
            });

            const cycleYearOptions = @json($energyCycles);

            // Move record
            $('#energyRequestTable').on('click', '.moveEnergyRequest', function () {

                const id = $(this).data('id');
                const notes = $(this).data('notes');
                const cycleyear = $(this).data('cycle'); // Must be set in HTML


                // Generate dropdown options dynamically
                let dropdownHTML = '<option value="" disabled selected>Select ...</option>';
                cycleYearOptions.forEach(cycle => {

                    dropdownHTML += `<option value="${cycle.id}" ${cycle.id == cycleyear ? 'selected' : ''}>${cycle.name}</option>`;
                });


                Swal.fire({
                    icon: 'warning',
                    title: 'Confirm Requested Household?',
                    html: `
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year:</label>
                                <select name="cycle_year" id="editCycleYear"
                                    class="swal2-select">
                                    ${dropdownHTML}
                                </select>
                            </fieldset>
                        </div>
                        <div >
                            <label class='col-md-12 control-label'>Confirmation Notes:</label>
                            <textarea id="editNotes" class="swal2-textarea" rows="1" cols="30">${notes}</textarea>
                        </div>
                    `,
                    showDenyButton: true,
                    confirmButtonText: 'Confirm',

                }).then((result) => {
                    if (result.isConfirmed) {

                        const updatedNotes = document.getElementById('editNotes').value;
                        const updatedCycle = document.getElementById('editCycleYear').value;
                        const updatedData = result.value;

                        $.ajax({
                            url: "{{ route('moveEnergyRequest') }}",
                            type: 'get',
                            data: {
                                id: id,
                                notes: updatedNotes,
                                cycleyear: updatedCycle
                            },
                            success: function (response) {
                                if (response.success == 1) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: response.msg,
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay!'
                                    }).then((result) => {
                                        $('#energyRequestTable').DataTable().draw();
                                    });
                                } else {

                                    alert("Invalid ID.");
                                }
                            }
                        });
                    }
                });
            });
            
            //  handle Start Working for Requested tab in-progress view
            $(document).on('click', '#requestedHouseholdsTable .moveEnergyRequest', function () {
                const id = $(this).data('id');
                const notes = $(this).data('notes') || '';
                const cycleyear = $(this).data('cycle') || '';

                let dropdownHTML = '<option value="" disabled selected>Select ...</option>';
                if (typeof cycleYearOptions !== 'undefined' && Array.isArray(cycleYearOptions)) {
                    cycleYearOptions.forEach(cycle => {
                        dropdownHTML += `<option value="${cycle.id}" ${cycle.id == cycleyear ? 'selected' : ''}>${cycle.name}</option>`;
                    });
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Confirm Requested Household?',
                    html: `
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year:</label>
                                <select name="cycle_year" id="editCycleYear" class="swal2-select">
                                    ${dropdownHTML}
                                </select>
                            </fieldset>
                        </div>
                        <div>
                            <label class='col-md-12 control-label'>Confirmation Notes:</label>
                            <textarea id="editNotes" class="swal2-textarea" rows="1" cols="30">${notes}</textarea>
                        </div>
                    `,
                    showDenyButton: true,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const updatedNotes = document.getElementById('editNotes').value;
                        const updatedCycle = document.getElementById('editCycleYear').value;

                        $.ajax({
                            url: "{{ route('moveEnergyRequest') }}",
                            type: 'get',
                            data: { id: id, notes: updatedNotes, cycleyear: updatedCycle },
                            success: function (response) {
                                if (response && response.success == 1) {
                                    Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' }).then(() => {
                                        if ($.fn.DataTable.isDataTable('.data-table-requested-households')) {
                                            $('.data-table-requested-households').DataTable().ajax.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.msg) ? response.msg : 'Invalid ID.' });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('moveEnergyRequest failed (requested tab):', status, error, xhr.responseText);
                                Swal.fire({ icon: 'error', title: 'Request failed', html: 'Status: ' + status + '<br>Error: ' + error });
                            }
                        });
                    }
                });
            });

            // Postpone handler for Requested tab
            $(document).on('click', '#requestedHouseholdsTable .postponedEnergyRequest', function () {
                var id = $(this).data('id');

                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you want to postpone this requested household?',
                    showDenyButton: true,
                    confirmButtonText: 'Confirm',
                    input: 'textarea',
                    inputPlaceholder: 'Enter your reason for postponing this requested household...',
                    inputAttributes: { 'aria-label': 'Enter your reason' },
                    showCancelButton: true,
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        $.ajax({
                            url: "{{ route('postponedEnergyRequest') }}",
                            type: 'get',
                            data: { id: id, reason: reason },
                            success: function (response) {
                                if (response && response.success == 1) {
                                    Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' }).then(() => {
                                        if ($.fn.DataTable.isDataTable('.data-table-requested-households')) {
                                            $('.data-table-requested-households').DataTable().ajax.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Error', text: (response && response.msg) ? response.msg : 'Invalid ID.' });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('postponedEnergyRequest failed (requested tab):', status, error, xhr.responseText);
                                Swal.fire({ icon: 'error', title: 'Request failed', html: 'Status: ' + status + '<br>Error: ' + error });
                            }
                        });
                    }
                });
            });

            // $('#energyRequestTable').on('click', '.moveEnergyRequest',function() {

            //     var id = $(this).data('id');
            //     var notes = $(this).data('notes');
            //     var cycleyear = $(this).data('cycle');

            //     let dropdownHTML = '';
            //     cycleYearOptions.forEach(year => {

            //         dropdownHTML += `<option value="${year}" ${year == cycleyear ? 'selected' : ''}>${year}</option>`;
            //     });

            //     Swal.fire({
            //         icon: 'warning',
            //         title: 'Confirm Requested Household?',
            //         html: `
            //             <div style="text-align:left">
            //                 <label><strong>Notes:</strong></label><br>
            //                 <textarea id="editNotes" class="swal2-textarea" rows="4">${notes}</textarea><br>

            //                 <label><strong>Cycle Year:</strong></label><br>
            //                 <select id="editCycleYear" class="swal2-select">
            //                     ${dropdownHTML}
            //                 </select>
            //             </div>
            //         `,
            //         showDenyButton: true,
            //         confirmButtonText: 'Confirm'
            //     }).then((result) => {

            //         if(result.isConfirmed) {
            //             $.ajax({
            //                 url: "{{ route('moveEnergyRequest') }}",
            //                 type: 'get',
            //                 data: {
            //                     id: id,
            //                     notes: notes,
            //                     cycleyear: cycleyear
            //                 },
            //                 success: function(response) {
            //                     if(response.success == 1) {

            //                         Swal.fire({
            //                             icon: 'success',
            //                             title: response.msg,
            //                             showDenyButton: false,
            //                             showCancelButton: false,
            //                             confirmButtonText: 'Okay!'
            //                         }).then((result) => {
            //                             $('#energyRequestTable').DataTable().draw();
            //                         });
            //                     } else {

            //                         alert("Invalid ID.");
            //                     }
            //                 }
            //             });
            //         } else if (result.isDenied) {

            //             Swal.fire('Changes are not saved', '', 'info')
            //         }
            //     });
            // }); 

            // Postponed record
            $('#energyRequestTable').on('click', '.postponedEnergyRequest', function () {
                var id = $(this).data('id');

                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you want to postpone this requested household?',
                    showDenyButton: true,
                    confirmButtonText: 'Confirm',
                    input: 'textarea',
                    inputPlaceholder: 'Enter your reason for postponing this requested household...',
                    inputAttributes: {
                        'aria-label': 'Enter your reason'
                    },
                    showCancelButton: true,
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        $.ajax({
                            url: "{{ route('postponedEnergyRequest') }}",
                            type: 'get',
                            data: {
                                id: id,
                                reason: reason
                            },
                            success: function (response) {
                                if (response.success == 1) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: response.msg,
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay!'
                                    }).then((result) => {
                                        $('#energyRequestTable').DataTable().draw();
                                    });
                                } else {

                                    alert("Invalid ID.");
                                }
                            }
                        });
                    } else if (result.isDenied) {

                        console.log('Deletion canceled');
                    }
                });
            });

            // Delete record
            $('#energyRequestTable').on('click', '.deleteEnergyRequest', function () {
                var id = $(this).data('id');

                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure you want to remove this requested household from the list?',
                    showDenyButton: true,
                    confirmButtonText: 'Confirm',
                    input: 'textarea',
                    inputPlaceholder: 'Enter your reason for deleting this requested household...',
                    inputAttributes: {
                        'aria-label': 'Enter your reason'
                    },
                    showCancelButton: true,
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        $.ajax({
                            url: "{{ route('deleteEnergyRequest') }}",
                            type: 'get',
                            data: {
                                id: id,
                                reason: reason
                            },
                            success: function (response) {
                                if (response.success == 1) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: response.msg,
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay!'
                                    }).then((result) => {
                                        $('#energyRequestTable').DataTable().draw();
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


            // Duplicate deleted-requested-households block removed to avoid DataTable reinitialisation
        });
    </script>
@endsection