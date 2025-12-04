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

.component-grid div {
    background: #f5f5f5;
    padding: 8px;
    border-radius: 4px;
}
.component-grid label { 
    margin-left: 5px;
    font-size: 15px;
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
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
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
                        <th class="text-center">Rated Solar Power (kW)</th>
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
        table = $('.data-table-energy-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-system.index') }}",
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
                {data: 'total_rated_power', name: 'total_rated_power'},
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
            if ($.fn.DataTable.isDataTable('.data-table-energy-system')) {
                $('.data-table-energy-system').DataTable().destroy();
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

    // Copy record
    $('#systemEnergyTable').on('click', '.copyEnergySystem', function () {

        var id = $(this).data('id');

        $.ajax({
            url: "energy-system/copy/" + id, 
            type: 'GET',
            success: function (data) {

                let systemOptions = '<option value="">-- Select a System --</option>';
                data.systems.forEach(function (system) {
                    systemOptions += `<option value="${system.id}">${system.name}</option>`;
                });

                let componentOptions = `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="selectAllComponents">
                        <label class="form-check-label fw-bold" for="selectAllComponents">Select All Components</label>
                    </div>
                `;
                data.components.forEach(function (component, index) {
                    const uniqueId = `component-${component.id}-${index}`;
                    componentOptions += `
                        <div class="form-check">
                            <input class="form-check-input component-checkbox" type="checkbox" name="components[]" value="${component.id}" id="${uniqueId}">
                            <label class="form-check-label" for="${uniqueId}">${component.name}</label>
                        </div>`;
                });

                Swal.fire({
                    title: 'Copy from Another System',
                    width: '60%',
                    html: `
                        <form id="copySystemForm">
                            <div style="text-align: left;">
                                <div class="form-group mb-3">
                                    <label for="systemSelect"><strong>Select Source System:</strong></label>
                                    <select id="systemSelect" name="system_id" class="swal2-select selectpicker" data-live-search="true" style="width: 100%;">
                                        ${systemOptions}
                                    </select>
                                </div>
                                
                                <div class="form-group mt-4">
                                    <label><strong>Select Components to Copy:</strong></label>
                                    <div class="component-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 10px;">
                                        ${componentOptions}
                                    </div>
                                </div>
                            </div>
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Copy',
                    preConfirm: () => {
                        const systemId = $('#systemSelect').val();
                        const selectedComponents = [];
                        $('input[name="components[]"]:checked').each(function () {
                            selectedComponents.push($(this).val());
                        });

                        if (!systemId || selectedComponents.length === 0) {
                            Swal.showValidationMessage(`Please select a system and at least one component.`);
                            return false;
                        }

                        return {
                            system_id: systemId,
                            components: selectedComponents
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        const formData = result.value;
                        $.ajax({
                            url: "/energy-system/copy/components/" + formData.system_id + "/" + formData.components + "/" + id, 
                            type: 'get',
                            data: {
                                system_id: formData.system_id,
                                components: formData.components,
                            },
                            success: function (response) {

                                console.log(response);
                                Swal.fire('Success', 'Components copied successfully!', 'success');
                                table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Failed to copy components.', 'error');
                            }
                        });
                    }
                });

                // Re-initialize selectpicker
                setTimeout(() => {
                    $('#systemSelect').selectpicker('render');
                }, 100);


                setTimeout(() => {

                    $('#systemSelect').selectpicker('render');

                    // Select All toggle
                    $('#selectAllComponents').on('change', function () {

                        const isChecked = $(this).is(':checked');
                        $('.component-checkbox').prop('checked', isChecked);
                    });

                    // Optional: If all checkboxes are manually checked/unchecked, sync the "Select All" checkbox
                    $('.component-checkbox').on('change', function () {

                        const total = $('.component-checkbox').length;
                        const checked = $('.component-checkbox:checked').length;
                        $('#selectAllComponents').prop('checked', total === checked);
                    });
                }, 100);

            }

        });
    });


</script>
@endsection