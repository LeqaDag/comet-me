@extends('layouts/layoutMaster')

@section('title', 'edit refrigerator issue')

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
        {{$refrigeratorIssue->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('refrigerator-issue.update', $refrigeratorIssue->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (English)</label>
                            <input type="text" name="english_name" class="form-control" 
                                value="{{$refrigeratorIssue->english_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (Arabic)</label>
                            <input type="text" name="arabic_name" class="form-control"
                                value="{{$refrigeratorIssue->arabic_name}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action Category</label>
                            <select name="action_category_id" data-live-search="true"
                                class="selectpicker form-control" id="refrigeratorActionCategoryEdit">
                                @if($refrigeratorIssue->refrigerator_action_id)
                                    <option value="{{$refrigeratorIssue->RefrigeratorAction->ActionCategory->id}}">
                                        {{$refrigeratorIssue->RefrigeratorAction->ActionCategory->english_name}}
                                    </option>
                                @endif 
                                @foreach($refrigeratorCategories as $actionCategory)
                                    <option value="{{$actionCategory->id}}">
                                        {{$actionCategory->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Refrigerator Action</label>
                            <select name="refrigerator_action_id" data-live-search="true"
                                class="selectpicker form-control" id="refrigeratorActionSelectedEdit">
                                @if($refrigeratorIssue->refrigerator_action_id)
                                    <option value="{{$refrigeratorIssue->refrigeratorAction->id}}">
                                        {{$refrigeratorIssue->refrigeratorAction->english_name}}
                                    </option>
                                @endif 
                                @foreach($refrigeratorActions as $refrigeratorAction)
                                    <option value="{{$refrigeratorAction->id}}">
                                        {{$refrigeratorAction->english_name}}
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
                                {{$refrigeratorIssue->notes}}
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

        $(document).on('change', '#refrigeratorActionCategoryEdit', function () {

            category_id = $(this).val();
            $.ajax({
                url: "/refrigerator-issue/get_by_action_category/" +  category_id,
                method: 'GET',
                success: function(data) {

                    var select = $('#refrigeratorActionSelectedEdit');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });
</script>
@endsection