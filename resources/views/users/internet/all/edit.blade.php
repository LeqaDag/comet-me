@extends('layouts/layoutMaster')

@section('title', 'edit internet user')

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


@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4"> 
    <span class="text-muted fw-light">Edit </span> 
    @if($allInternetHolder->Household)
        {{$allInternetHolder->Household->english_name}}
    @else @if($allInternetHolder->PublicStructure)
        {{$allInternetHolder->PublicStructure->english_name}}
    @endif
    @endif
    
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('internet-user.update', $allInternetHolder->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" class="form-control" disabled>
                                @if($allInternetHolder->Community)
                                <option value="{{$allInternetHolder->Community->id}}" disabled selected>
                                    {{$allInternetHolder->Community->english_name}}
                                </option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet Holder</label>
                            <select name="household_id" class="form-control" disabled>
                                @if($allInternetHolder->Household)
                                <option value="{{$allInternetHolder->Household->id}}" disabled selected>
                                    {{$allInternetHolder->Household->english_name}}
                                </option>
                                @else
                                <option value="{{$allInternetHolder->PublicStructure->id}}" disabled selected>
                                    {{$allInternetHolder->PublicStructure->english_name}}
                                </option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    @if($allInternetHolder->Household)
                        @if($allInternetHolder->Household->out_of_comet == 1)
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>English Name</label>
                                    <input type="text" name="household_english_name" class="form-control" 
                                    value="{{$allInternetHolder->Household->english_name}}" required>
                                </fieldset>
                            </div>
                        @endif
                    @else 
    
                    @if($allInternetHolder->PublicStructure->out_of_comet == 1)
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="public_english_name" class="form-control" 
                                value="{{$allInternetHolder->PublicStructure->english_name}}"required>
                            </fieldset>
                        </div>
                    @endif
                    @endif
                </div>

                <hr>
                <div class="row">
                    <h5>Donors</h5>
                </div>
                @if(count($allInternetHolderDonors) > 0)

                    <table id="allInternetHolderDonorsTable" class="table table-striped data-table-energy-donors my-2">
                        
                        <tbody>
                            @foreach($allInternetHolderDonors as $allInternetHolderDonor)
                            <tr id="allInternetHolderDonorRow">
                                <td class="text-center">
                                    {{$allInternetHolderDonor->Donor->donor_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetDonor" id="deleteInternetDonor" 
                                        data-id="{{$allInternetHolderDonor->id}}">
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

<script type="text/javascript">
    $(function () {

        // delete energy donor
        $('#allInternetHolderDonorsTable').on('click', '.deleteInternetDonor',function() {
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
                        url: "{{ route('deleteInternetDonor') }}",
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