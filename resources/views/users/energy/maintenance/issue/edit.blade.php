@extends('layouts/layoutMaster')

@section('title', 'edit energy issue')

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
        {{$energyIssue->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-issue.update', $energyIssue->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (English)</label>
                            <input type="text" name="english_name" class="form-control" 
                                value="{{$energyIssue->english_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (Arabic)</label>
                            <input type="text" name="arabic_name" class="form-control"
                                value="{{$energyIssue->arabic_name}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action Category</label>
                            <select name="action_category_id" data-live-search="true"
                                class="selectpicker form-control" id="energyActionCategoryEdit">
                                @if($energyIssue->energy_action_id)
                                    <option value="{{$energyIssue->EnergyAction->ActionCategory->id}}">
                                        {{$energyIssue->EnergyAction->ActionCategory->english_name}}
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Action</label>
                            <select name="energy_action_id" data-live-search="true"
                                class="selectpicker form-control" id="energyActionSelectedEdit">
                                @if($energyIssue->energy_action_id)
                                    <option value="{{$energyIssue->EnergyAction->id}}">
                                        {{$energyIssue->EnergyAction->english_name}}
                                    </option>
                                @endif 
                                @foreach($energyActions as $energyAction)
                                    <option value="{{$energyAction->id}}">
                                        {{$energyAction->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Issue Type</label>
                            <select name="energy_maintenance_issue_type_id" data-live-search="true"
                                class="selectpicker form-control">
                                @if($energyIssue->energy_maintenance_issue_type_id)
                                    <option value="{{$energyIssue->energy_maintenance_issue_type_id}}">
                                        {{$energyIssue->EnergyMaintenanceIssueType->name}}
                                    </option>
                                @endif 
                                @foreach($energyIssueTypes as $energyIssueType)
                                    <option value="{{$energyIssueType->id}}">
                                        {{$energyIssueType->name}}
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
                                {{$energyIssue->notes}}
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
<script>
    $(document).ready(function() {

        $(document).on('change', '#energyActionCategoryEdit', function () {

            category_id = $(this).val();
            $.ajax({
                url: "/energy-issue/get_by_action_category/" +  category_id,
                method: 'GET',
                success: function(data) {

                    var select = $('#energyActionSelectedEdit');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });
</script>
@endsection