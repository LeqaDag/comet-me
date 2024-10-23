@extends('layouts/layoutMaster')

@section('title', 'edit public structure')

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
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <label class='col-md-12 control-label'>English Name</label>
                        <input class="form-control" name="english_name"
                            value="{{$publicStructure->english_name}}"/>    
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <label class='col-md-12 control-label'>Arabic Name</label>
                        <input class="form-control" name="arabic_name"
                            value="{{$publicStructure->arabic_name}}"/>    
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Compound</label>
                            <select name="compound_id"
                                class="selectpicker form-control" data-live-search="true"  
                                    required>
                                @if($publicStructure->compound_id)
                                    <option disabled selected>
                                        {{$publicStructure->Compound->english_name}}
                                    </option>
                                @else 
                                    <option disabled selected>Choose one...</option>
                                @endif
                                
                                @foreach($compounds as $compound)
                                <option value="{{$compound->id}}">
                                    {{$compound->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
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
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Out of comet?</label>
                            <select name="out_of_comet" id="outOfComet" data-live-search="true"
                                class="selectpicker form-control">
                                @if($publicStructure->out_of_comet == 1) 
                                    <option selected disabled>Yes</option>
                                    <option value="0">No</option>
                                @else @if($publicStructure->out_of_comet == 0) 
                                    <option selected disabled>No</option>
                                    <option value="1">Yes</option>
                                @else
                                <option disabled selected>Choose one...</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                @endif
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" 
                                class="selectpicker form-control" data-live-search="true" >
                                
                                @if($publicStructure->energy_system_type_id)
                                <option value="{{$publicStructure->EnergySystemType->id}}" disabled selected>
                                    {{$publicStructure->EnergySystemType->name}}
                                </option>
                                @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">
                                    {{$energySystemType->name}}
                                </option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">
                                    {{$energySystemType->name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" data-live-search="true"
                            class="selectpicker form-control" >
                            @if($publicStructure->energy_system_cycle_id)
                                <option disabled selected>
                                    {{$publicStructure->EnergySystemCycle->name}}
                                </option>
                                @foreach($energyCycles as $energyCycle)
                                <option value="{{$energyCycle->id}}">
                                    {{$energyCycle->name}}
                                </option>
                                @endforeach
                            @else
                            <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                <option value="{{$energyCycle->id}}">
                                    {{$energyCycle->name}}
                                </option>
                                @endforeach
                            @endif
                            </select>
                        </fieldset>
                    </div>
                </div>
                @if($publicStructure->public_structure_category_id1 == 1 ||
                    $publicStructure->public_structure_category_id2 == 1 ||
                    $publicStructure->public_structure_category_id3 == 1)
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Grade From</label>
                                @if($schoolPublicStructure)
                                    <input type="number" class="form-control" name="grade_from"
                                        value="{{$schoolPublicStructure->grade_from}}"/>    
                                @else
                                    <input type="number" class="form-control" name="grade_from"/>  
                                @endif
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Grade To</label>
                                @if($schoolPublicStructure) 
                                    <input type="number" class="form-control" name="grade_to"
                                        value="{{$schoolPublicStructure->grade_to}}"/>    
                                @else
                                    <input type="number" class="form-control" name="grade_to"/>  
                                @endif   
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Boys</label>
                                @if($schoolPublicStructure) 
                                    <input type="number" class="form-control" name="number_of_boys"
                                        value="{{$schoolPublicStructure->number_of_boys}}"/>    
                                @else
                                    <input type="number" class="form-control" name="number_of_boys"/>  
                                @endif  
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Girls</label>
                                @if($schoolPublicStructure) 
                                    <input type="number" class="form-control" name="number_of_girls"
                                        value="{{$schoolPublicStructure->number_of_girls}}"/>    
                                @else
                                    <input type="number" class="form-control" name="number_of_girls"/>  
                                @endif   
                            </fieldset>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <h5>What communities does it serve</h5>
                    </div>
                    @if(count($schoolCommunities) > 0)
 
                        <table id="schoolCommunitiesTable" 
                            class="table table-striped data-table-school-community my-2">
                            
                            <tbody>
                                @foreach($schoolCommunities as $schoolCommunity)
                                <tr id="schoolCommunityRow">
                                    <td class="text-center">
                                        {{$schoolCommunity->Community->english_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteschoolCommunity" id="deleteschoolCommunity" 
                                            data-id="{{$schoolCommunity->id}}">
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
                                    <label class='col-md-12 control-label'>Add more Communities</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="communities[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($communities as $community)
                                            <option value="{{$community->id}}">
                                                {{$community->english_name}}
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
                                    <label class='col-md-12 control-label'>Add Communities</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="new_communities[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($communities as $community)
                                            <option value="{{$community->id}}">
                                                {{$community->english_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif
                @endif

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

<script type="text/javascript">
    $(function () {

        // delete school community
        $('#schoolCommunitiesTable').on('click', '.deleteschoolCommunity',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this served community?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteschoolCommunity') }}",
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
                                    $ele.fadeOut(1000, function () {
                                        $ele.remove();
                                    });
                                });
                            } 
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });
    });
</script>

@endsection