@extends('layouts/layoutMaster')

@section('title', 'edit water system')

@include('layouts.all')

<style>
    label, input {
    display: block;
}

label {
    margin-top: 20px;
}
</style>
@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$waterSystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('water-system.update', $waterSystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Connectors</label>
                            <select name="water_connector_id" class="form-control">
                                @if($waterConnectors->water_connector_id)
                                    <option value="{{$waterConnectors->Connector->id}}" disabled selected>
                                        {{$waterConnectors->Connector->model}}
                                    </option>
                                    @foreach($connectors as $connector)
                                    <option value="{{$connector->id}}">
                                        {{$connector->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($connectors as $connector)
                                    <option value="{{$connector->id}}">
                                        {{$connector->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filters</label>
                            <select name="water_filter_id" class="form-control">
                                @if($waterFilters->water_filter_id)
                                    <option value="{{$waterFilters->Filter->id}}" disabled selected>
                                        {{$waterFilters->Filter->model}}
                                    </option>
                                    @foreach($filters as $filter)
                                    <option value="{{$filter->id}}">
                                        {{$filter->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($filters as $filter)
                                    <option value="{{$filter->id}}">
                                        {{$filter->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Pipes</label>
                            <select name="water_pipe_id" class="form-control">
                                @if($waterPipes->water_pipe_id)
                                    <option value="{{$waterPipes->Pipe->id}}" disabled selected>
                                        {{$waterPipes->Pipe->model}}
                                    </option>
                                    @foreach($pipes as $pipe)
                                    <option value="{{$pipe->id}}">
                                        {{$pipe->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($pipes as $pipe)
                                    <option value="{{$pipe->id}}">
                                        {{$pipe->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Pumps</label>
                            <select name="water_pump_id" class="form-control">
                                @if($waterPump->water_pump_id)
                                    <option value="{{$waterPump->Pump->id}}" disabled selected>
                                        {{$waterPump->Pump->model}}
                                    </option>
                                    @foreach($pumps as $pump)
                                    <option value="{{$pump->id}}">
                                        {{$pump->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($pumps as $pump)
                                    <option value="{{$pump->id}}">
                                        {{$pump->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 

                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Tanks</label>
                            <select name="water_tank_id" class="form-control">
                                @if($waterTank->water_tank_id)
                                    <option value="{{$waterTank->Tank->id}}" disabled selected>
                                        {{$waterTank->Tank->model}}
                                    </option>
                                    @foreach($tanks as $tank)
                                    <option value="{{$tank->id}}">
                                        {{$tank->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($tanks as $tank)
                                    <option value="{{$tank->id}}">
                                        {{$tank->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                </div>

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
@endsection