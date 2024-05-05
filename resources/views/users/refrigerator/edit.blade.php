@extends('layouts/layoutMaster')

@section('title', 'edit refrigerator holder')

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
    @if($refrigeratorHolder->household_id)
 
        {{$refrigeratorHolder->Household->english_name}}
    @else @if($refrigeratorHolder->public_structure_id)

        {{$refrigeratorHolder->PublicStructure->english_name}}
    @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('refrigerator-user.update', $refrigeratorHolder->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($refrigeratorHolder->community_id)
                                    <option value="{{$refrigeratorHolder->community_id}}">
                                        {{$refrigeratorHolder->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                        @if($refrigeratorHolder->household_id)

                            <label class='col-md-12 control-label'>User</label>
                            <select class="selectpicker form-control" name="household_id" data-live-search="true">
                                <option value="{{$refrigeratorHolder->Household->english_name}}" disabled selected>
                                    {{$refrigeratorHolder->Household->english_name}}
                                </option>
                                @foreach($refrigeratorUsers as $refrigeratorUser)
                                    <option value="{{$refrigeratorUser->id}}">
                                        {{$refrigeratorUser->english_name}}
                                    </option>
                                @endforeach  
                            </select>
                            
                        @else @if($refrigeratorHolder->public_structure_id)

                            <label class='col-md-12 control-label'>Public</label>
                            <select class="selectpicker form-control" name="public_structure_id" data-live-search="true">
                                <option value="{{$refrigeratorHolder->PublicStructure->english_name}}" disabled selected>
                                    {{$refrigeratorHolder->PublicStructure->english_name}}
                                </option>
                                @foreach($refrigeratorUsers as $refrigeratorUser)
                                    <option value="{{$refrigeratorUser->id}}">
                                        {{$refrigeratorUser->english_name}}
                                    </option>
                                @endforeach  
                            </select>
                        @endif
                        @endif
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    @if($refrigeratorHolder->household_id)
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="number" name="phone_number" class="form-control"
                                    value="{{$refrigeratorHolder->Household->phone_number}}">
                            </fieldset>
                        </div>
                    @endif
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Refrigerator Type</label>
                            <select name="refrigerator_type_id" class="form-control">
                            @if($refrigeratorHolder->refrigerator_type_id)
                                <option value="{{$refrigeratorHolder->refrigerator_type_id}}">
                                    {{$refrigeratorHolder->refrigerator_type_id}}
                                </option>
                                <option value="No frost">No frost</option>
                                <option value="De frost">De frost</option>
                            @else
                                <option disabled selected>Choose one...</option>
                                <option value="No frost">No frost</option>
                                <option value="De frost">De frost</option>
                            @endif
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of Refrigerator</label>
                            <input type="number" name="number_of_fridge" 
                                value="{{$refrigeratorHolder->number_of_fridge}}" class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date</label>
                            <input type="date" name="date" value="{{$refrigeratorHolder->date}}"
                                class="form-control">
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Year</label>
                            <input type="number" name="year" value="{{$refrigeratorHolder->year}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is Paid?</label>
                            <select name="is_paid" class="form-control">
                                @if($refrigeratorHolder->is_paid)
                                    <option value="{{$refrigeratorHolder->is_paid}}">
                                        {{$refrigeratorHolder->is_paid}}
                                    </option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Free">Free</option>
                                @else
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Free">Free</option>
                                @endif  
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Payment</label>
                            <input type="number" name="payment" value="{{$refrigeratorHolder->payment}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Receipt Number</label>
                            @if(count($refrigeratorHolderNumber) > 0)
                                <input type="text" name="receive_number" class="form-control"
                                value="{{$refrigeratorHolderNumber[0]->receive_number}}">
                            @else
                                <input type="text" name="receive_number" class="form-control"
                                value="">
                            @endif
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="1">
                            {{$refrigeratorHolder->notes}}
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