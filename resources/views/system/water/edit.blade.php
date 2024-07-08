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
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water System Type</label>
                            <select name="water_system_type_id" class="selectpicker form-control"
                                    id="waterSystemTypeChange" data-live-search="true">
                                @if($waterSystem->water_system_type_id)
                                    <option disabled selected>{{$waterSystem->WaterSystemType->type}}</option>
                                @endif
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
                            <input type="text" name="name" value="{{$waterSystem->name}}"
                            class="form-control" required>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communityWaterSystem"
                                required>
                                @if($waterSystem->community_id)
                                    <option disabled selected>{{$waterSystem->Community->english_name}}</option>
                                @endif
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
                            class="form-control" value="{{$waterSystem->year}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 1</label>
                            <input type="number" name="upgrade_year1" 
                            class="form-control" value="{{$waterSystem->upgrade_year1}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 2</label>
                            <input type="number" name="upgrade_year2" 
                            class="form-control" value="{{$waterSystem->upgrade_year2}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Description</label>
                            <textarea name="description" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                                {{$waterSystem->description}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                                {{$waterSystem->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <br>
                <hr>

                <div class="row">
                    <h5>Tanks</h5>
                </div>
                @if(count($waterTanks) > 0) 

                    <table id="waterTanksTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($waterTanks as $waterTank)
                            <tr id="waterTankRow">
                                <td class="text-center">
                                    {{$waterTank->model}}
                                </td>
                                <td class="text-center">
                                    {{$waterTank->tank_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWaterTank" id="deleteWaterTank" 
                                        data-id="{{$waterTank->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add More Tanks</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_tanks[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($tanks as $tank)
                                        <option value="{{$tank->id}}">
                                            {{$tank->model}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Tanks</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_tanks[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($tanks as $tank)
                                        <option value="{{$tank->id}}">
                                            {{$tank->model}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif
                <br>
                <hr>

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