@extends('layouts/layoutMaster')

@section('title', 'create water system')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Water System
</h4>
 
<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('water-system')}}" enctype="multipart/form-data" >
                @csrf

                <div class="row"> 
                    <h6>General Details</h6> 
                </div>
                <div class="row"> 
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water System Type</label>
                            <select name="water_system_type_id" class="selectpicker form-control"
                                    id="waterSystemTypeChange" data-live-search="true" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($waterSystemTypes as $waterSystemTypes)
                                    <option value="{{$waterSystemTypes->id}}">
                                        {{$waterSystemTypes->type}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" 
                            class="form-control" required>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communityWaterSystem"
                                required >
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Start Year</label>
                            <input type="number" name="year" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select class="selectpicker form-control" name="water_system_cycle_id" 
                                data-live-search="true" id="waterCycleYear"
                                required >
                                <option disabled selected>Choose one...</option>
                                @foreach($waterSystemCycles as $waterSystemCycle)
                                <option value="{{$waterSystemCycle->id}}">{{$waterSystemCycle->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 1</label>
                            <input type="number" name="upgrade_year1" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 2</label>
                            <input type="number" name="upgrade_year2" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Description</label>
                            <textarea name="description" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                            </textarea>
                        </fieldset>
                    </div>
                </div>
<!-- 
                <hr>
                
                <div class="row">
                    <h6>Tanks</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveTank">
                            <tr>
                                <th>Tanks Model</th>
                                <th>Unit</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="tanks_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterTanks as $waterTank)
                                            <option value="{{$waterTank->id}}">
                                                {{$waterTank->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="tank_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveTankButton" 
                                        class="btn btn-outline-primary">
                                        Add Tank Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <h6>Pumps</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemovePump">
                            <tr>
                                <th>Pumps Model</th>
                                <th>Unit</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="pumps_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterPumps as $waterPump)
                                            <option value="{{$waterPump->id}}">
                                                {{$waterPump->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="pump_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemovePumpButton" 
                                        class="btn btn-outline-primary">
                                        Add Pump Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <h6>Pipes</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemovePipe">
                            <tr>
                                <th>Pipes Model</th>
                                <th>Unit</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="pipes_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterPipes as $waterPipe)
                                            <option value="{{$waterPipe->id}}">
                                                {{$waterPipe->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="pipe_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemovePipeButton" 
                                        class="btn btn-outline-primary">
                                        Add Pipe Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <h6>Filters</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveFilter">
                            <tr>
                                <th>Filters Model</th>
                                <th>Unit</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="filters_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterFilters as $waterFilter)
                                            <option value="{{$waterFilter->id}}">
                                                {{$waterFilter->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="filter_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveFilterButton" 
                                        class="btn btn-outline-primary">
                                        Add Filter Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <h6>Connectors</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveConnector">
                            <tr>
                                <th>Connectors Model</th>
                                <th>Unit</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="connectors_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterConnectors as $waterConnector)
                                            <option value="{{$waterConnector->id}}">
                                                {{$waterConnector->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="connector_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveConnectorButton" 
                                        class="btn btn-outline-primary">
                                        Add Connector Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div> -->


                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    var tank_count = 0;
    var pump_count = 0;
    var pipe_count = 0;
    var filter_count = 0;
    var connector_count = 0;

    // Tank
    $(document).on('click', '#addRemoveTankButton', function () {

        ++tank_count;
        $("#addRemoveTank").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ tank_count +'" name="tank_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeTank">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeTank', function () {

        $(this).parents('tr').remove();
    });

    // Pump
    $(document).on('click', '#addRemovePumpButton', function () {

        ++pump_count;
        $("#addRemovePump").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ pump_count +'" name="pump_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removePump">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePump', function () {

        $(this).parents('tr').remove();
    });

    
    // Pipe
    $(document).on('click', '#addRemovePipeButton', function () {

        ++pipe_count;
        $("#addRemovePipe").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ pipe_count +'" name="pipe_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removePipe">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePipe', function () {

        $(this).parents('tr').remove();
    });


    // Filter
    $(document).on('click', '#addRemoveFilterButton', function () {

        ++filter_count;
        $("#addRemoveFilter").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ filter_count +'" name="filter_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeFilter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeFilter', function () {

        $(this).parents('tr').remove();
    });

    // Connector
    $(document).on('click', '#addRemoveConnectorButton', function () {

        ++connector_count;
        $("#addRemoveConnector").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ connector_count +'" name="connector_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeConnector">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeConnector', function () {

        $(this).parents('tr').remove();
    });

    $(document).on('change', '#waterSystemTypeChange', function () {

        var waterType = $(this).val();

        if(waterType == 4) $('#communityWaterSystem').disabled = false;
    });

</script>

@endsection