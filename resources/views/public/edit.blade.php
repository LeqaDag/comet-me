@extends('layouts/layoutMaster')

@section('title', 'edit mg incident')

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
    <span class="text-muted fw-light">Edit </span> {{$publicStructure->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('public-structure.update', $publicStructure->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <label class='col-md-12 control-label'>English Name</label>
                        <input class="form-control" name="english_name"
                            value="{{$publicStructure->english_name}}"/>    
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <label class='col-md-12 control-label'>Arabic Name</label>
                        <input class="form-control" name="arabic_name"
                            value="{{$publicStructure->arabic_name}}"/>    
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Public Category 1</label>
                            <select name="public_structure_category_id1"
                                class="selectpicker form-control" data-live-search="true"  
                                    required>
                                @if($publicStructure->public_structure_category_id1)
                                    <option disabled selected>
                                        {{$publicStructure->Category1->name}}
                                    </option>
                                @else 
                                    <option disabled selected>Choose one...</option>
                                @endif
                                
                                @foreach($publicCategories as $publicCategory)
                                <option value="{{$publicCategory->id}}">
                                    {{$publicCategory->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Public Category 2</label>
                            <select name="public_structure_category_id2"
                                class="selectpicker form-control" data-live-search="true"  
                                    required>
                                @if($publicStructure->public_structure_category_id2)
                                    <option disabled selected>
                                        {{$publicStructure->Category2->name}}
                                    </option>
                                @else 
                                    <option disabled selected>Choose one...</option>
                                @endif
                                
                                @foreach($publicCategories as $publicCategory)
                                <option value="{{$publicCategory->id}}">
                                    {{$publicCategory->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Public Category 3</label>
                            <select name="public_structure_category_id3"
                                class="selectpicker form-control" data-live-search="true"  
                                    required>
                                @if($publicStructure->public_structure_category_id3)
                                    <option disabled selected>
                                        {{$publicStructure->Category3->name}}
                                    </option>
                                @else 
                                    <option disabled selected>Choose one...</option>
                                @endif
                                
                                @foreach($publicCategories as $publicCategory)
                                <option value="{{$publicCategory->id}}">
                                    {{$publicCategory->name}}
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
                            {{$publicStructure->notes}}
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