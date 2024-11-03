@extends('layouts/layoutMaster')

@section('title', 'edit donor')

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
    @if($communityDonor->compound_id)
        {{$communityDonor->Compound->english_name}}
    @else
        {{$communityDonor->Community->english_name}}
    @endif
    <span class="text-primary">{{$communityDonor->ServiceType->service_name}}</span>
    <span class="text-muted fw-light">
     - 
    Donors Information </span> 
</h4> 

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('donor.update', $communityDonor->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="row">
                        <h5>Current Donors</h5>
                    </div>

                    @if(count($communityCompoundDonors) > 0) 

                        <table id="communityCompoundDonorsTable" 
                            class="table table-striped data-table-fbs-equipments my-2">
                            
                            <tbody>
                                @foreach($communityCompoundDonors as $communityCompoundDonor)
                                <tr id="communityCompoundDonorRow">
                                    <td class="text-center">
                                        {{$communityCompoundDonor->donor_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteDonor" id="deleteDonor" 
                                            data-id="{{$communityCompoundDonor->id}}">
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
                                    <label class='col-md-12 control-label'>Add More Donors</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="more_donors[]">
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
                                            <option value="{{$donor->id}}">
                                                {{$donor->donor_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif
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

    // delete donor
    $('#communityCompoundDonorsTable').on('click', '.deleteDonor',function() {
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
                    url: "{{ route('deleteCommunityDonor') }}",
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
</script>
@endsection