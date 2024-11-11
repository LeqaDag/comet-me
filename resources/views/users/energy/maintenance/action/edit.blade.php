@extends('layouts/layoutMaster')

@section('title', 'edit energy action')

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
    <span class="text-muted fw-light">Edit </span> 
        {{$energyAction->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-action.update', $energyAction->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (English)</label>
                            <input type="text" name="english_name" class="form-control" 
                                value="{{$energyAction->english_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (Arabic)</label>
                            <input type="text" name="arabic_name" class="form-control"
                                value="{{$energyAction->arabic_name}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action Category</label>
                            <select name="energy_action_category_id" data-live-search="true"
                                class="selectpicker form-control" required>
                                @if($energyAction->energy_action_category_id)
                                    <option value="{{$energyAction->energy_action_category_id}}">
                                        {{$energyAction->EnergyActionCategory->english_name}}
                                    </option>
                                @endif 
                                @foreach($energyCategories as $actionCategory)
                                    <option value="{{$actionCategory->id}}">
                                        {{$actionCategory->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$energyAction->notes}}
                            </textarea>
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