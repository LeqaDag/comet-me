@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit water user')

@include('layouts.all')

<style>
    label, input {

        display: block;
    }

    .dropdown-toggle {

        height: 40px;
    }

    label {

        margin-top: 20px;
    }
</style> 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4"> 
    <span class="text-muted fw-light">Edit </span> 
    @if($allWaterHolder->Household)
        {{$allWaterHolder->Household->english_name}}
    @else @if($allWaterHolder->PublicStructure)
        {{$allWaterHolder->PublicStructure->english_name}}
    @endif
    @endif
    
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-water.update', $allWaterHolder->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" class="form-control" disabled>
                                @if($allWaterHolder->Community)
                                <option value="{{$allWaterHolder->Community->id}}" disabled selected>
                                    {{$allWaterHolder->Community->english_name}}
                                </option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water Holder</label>
                            <select name="household_id" class="form-control" disabled>
                                @if($allWaterHolder->Household)
                                <option value="{{$allWaterHolder->Household->id}}" disabled selected>
                                    {{$allWaterHolder->Household->english_name}}
                                </option>
                                @else
                                <option value="{{$allWaterHolder->PublicStructure->id}}" disabled selected>
                                    {{$allWaterHolder->PublicStructure->english_name}}
                                </option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                @if($h2oUser)
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>H2O System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of H2O</label>
                            <input type="number" name="number_of_h20" 
                            value="{{$h2oUser->number_of_h20}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Status</label>
                            <select name="h2o_status_id" class="form-control" >
                                @if($h2oUser->H2oStatus)
                                    <option disabled selected>
                                        {{$h2oUser->H2oStatus->status}}
                                    </option>
                                    @foreach($h2oStatuses as $h2oStatus)
                                    <option value="{{$h2oStatus->id}}">
                                        {{$h2oStatus->status}}
                                    </option>
                                    @endforeach
                                @else
                                    <option selected disabled>Choose one..</option>
                                    @foreach($h2oStatuses as $h2oStatus)
                                        <option value="{{$h2oStatus->id}}">
                                            {{$h2oStatus->status}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of BSF</label>
                            <input type="number" name="number_of_bsf" 
                            value="{{$h2oUser->number_of_bsf}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSF Status</label>
                            <select name="bsf_status_id" class="form-control" >
                                @if($h2oUser->BsfStatus)
                                    <option  disabled selected>
                                        {{$h2oUser->BsfStatus->name}}
                                    </option>
                                    @foreach($bsfStatuses as $bsfStatus)
                                        <option value="{{$bsfStatus->id}}">
                                            {{$bsfStatus->name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option selected disabled>Choose one..</option>
                                    @foreach($bsfStatuses as $bsfStatus)
                                        <option value="{{$bsfStatus->id}}">
                                            {{$bsfStatus->name}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Request Date</label>
                            <input type="date" name="h2o_request_date" 
                            value="{{$h2oUser->h2o_request_date}}"
                                class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" 
                            value="{{$h2oUser->installation_year}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Date</label>
                                <input type="date" name="h2o_installation_date" 
                                value="{{$h2oUser->h2o_installation_date}}"
                                    class="form-control">
                            </fieldset>
                        </div>
                </div>
                @endif


                @if($h2oPublic)
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>H2O System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of H2O</label>
                            <input type="number" name="number_of_h20" 
                            value="{{$h2oPublic->number_of_h20}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Status</label>
                            <select name="h2o_status_id" class="form-control" >
                                @if($h2oPublic->H2oStatus)
                                    <option disabled selected>
                                        {{$h2oPublic->H2oStatus->status}}
                                    </option>
                                    @foreach($h2oStatuses as $h2oStatus)
                                    <option value="{{$h2oStatus->id}}">
                                        {{$h2oStatus->status}}
                                    </option>
                                    @endforeach
                                @else
                                    <option selected disabled>Choose one..</option>
                                    @foreach($h2oStatuses as $h2oStatus)
                                        <option value="{{$h2oStatus->id}}">
                                            {{$h2oStatus->status}}
                                        </option>
                                        @endforeach
                                    @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of BSF</label>
                            <input type="number" name="number_of_bsf" 
                            value="{{$h2oPublic->number_of_bsf}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSF Status</label>
                            <select name="bsf_status_id" class="form-control" >
                                @if($h2oPublic->BsfStatus)
                                    <option  disabled selected>
                                        {{$h2oPublic->BsfStatus->name}}
                                    </option>
                                    @foreach($bsfStatuses as $bsfStatus)
                                    <option value="{{$bsfStatus->id}}">
                                        {{$bsfStatus->name}}
                                    </option>
                                    @endforeach
                                @else
                                    <option selected disabled>Choose one..</option>
                                @foreach($bsfStatuses as $bsfStatus)
                                    <option value="{{$bsfStatus->id}}">
                                        {{$bsfStatus->name}}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>H2O Request Date</label>
                            <input type="date" name="h2o_request_date" 
                            value="{{$h2oPublic->h2o_request_date}}"
                                class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" 
                            value="{{$h2oPublic->installation_year}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Date</label>
                                <input type="date" name="h2o_installation_date" 
                                value="{{$h2oPublic->h2o_installation_date}}"
                                    class="form-control">
                            </fieldset>
                        </div>
                </div>
                @endif

                @if($gridUser)
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Grid System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large</label>
                            @if($gridUser)
                            <input type="number" name="grid_integration_large" 
                            value="{{$gridUser->grid_integration_large}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_large" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large Date</label>
                            @if($gridUser)
                            <input type="date" name="large_date" 
                            value="{{$gridUser->large_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="large_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small</label>
                            @if($gridUser)
                            <input type="number" name="grid_integration_small" 
                            value="{{$gridUser->grid_integration_small}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_small"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small Date</label>
                            @if($gridUser)
                            <input type="date" name="small_date" 
                            value="{{$gridUser->small_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="small_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Request Date</label>
                            @if($gridUser)
                            <input type="date" name="request_date" 
                            value="{{$gridUser->request_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="request_date" 
                            class="form-control">

                            @endif
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Confirmation</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Delivery</label>
                            <select name="is_delivery" class="form-control">
                                @if($gridUser)
                                    <option disabled selected>{{$gridUser->is_delivery}}</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                @else

                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Paid</label>
                            <select name="is_paid" class="form-control">
                            @if($gridUser)
                                <option disabled selected>{{$gridUser->is_paid}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="NA">NA</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Complete</label>
                            <select name="is_complete" class="form-control">
                            @if($gridUser)
                                <option disabled selected>{{$gridUser->is_complete}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>
                @endif


                @if($gridPublic)
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Grid System</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large</label>
                            @if($gridPublic)
                            <input type="number" name="grid_integration_large" 
                            value="{{$gridPublic->grid_integration_large}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_large" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Large Date</label>
                            @if($gridPublic)
                            <input type="date" name="large_date" 
                            value="{{$gridPublic->large_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="large_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small</label>
                            @if($gridPublic)
                            <input type="number" name="grid_integration_small" 
                            value="{{$gridPublic->grid_integration_small}}"
                            class="form-control">
                            @else
                            <input type="number" name="grid_integration_small"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Integration Small Date</label>
                            @if($gridPublic)
                            <input type="date" name="small_date" 
                            value="{{$gridPublic->small_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="small_date" 
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Request Date</label>
                            @if($gridPublic)
                            <input type="date" name="request_date" 
                            value="{{$gridPublic->request_date}}"
                            class="form-control">
                            @else
                            <input type="date" name="request_date" 
                            class="form-control">

                            @endif
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 headingLabel'>Confirmation</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Delivery</label>
                            <select name="is_delivery" class="form-control">
                                @if($gridPublic)
                                    <option disabled selected>{{$gridPublic->is_delivery}}</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                @else

                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Paid</label>
                            <select name="is_paid" class="form-control">
                            @if($gridPublic)
                                <option disabled selected>{{$gridPublic->is_paid}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="NA">NA</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Complete</label>
                            <select name="is_complete" class="form-control">
                            @if($gridPublic)
                                <option disabled selected>{{$gridPublic->is_complete}}</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>
                @endif

                <hr>
                <div class="row">
                    <h5>Donors</h5>
                </div>
                @if(count($allWaterHolderDonors) > 0)

                    <table id="allWaterHolderDonorsTable" class="table table-striped data-table-energy-donors my-2">
                        
                        <tbody>
                            @foreach($allWaterHolderDonors as $allWaterHolderDonor)
                            <tr id="allWaterHolderDonorRow">
                                <td class="text-center">
                                    {{$allWaterHolderDonor->Donor->donor_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWaterDonor" id="deleteWaterDonor" 
                                        data-id="{{$allWaterHolderDonor->id}}">
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
                                <label class='col-md-12 control-label'>Add more donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">
                                            {{$donor->donor_name}}
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
                                <label class='col-md-12 control-label'>Add Donors</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_donors[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{$donor->id}}">{{$donor->donor_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $(function () {

        // delete energy donor
        $('#allWaterHolderDonorsTable').on('click', '.deleteWaterDonor',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this donor?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterDonor') }}",
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