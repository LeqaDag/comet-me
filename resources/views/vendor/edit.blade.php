@extends('layouts/layoutMaster')

@section('title', 'edit vending point')

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
        {{$vendingPoint->english_name}} - 
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('vending-point.update', $vendingPoint->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>English Name</label>
                            <input type="text" name="english_name" 
                            class="form-control" value="{{$vendingPoint->english_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Arabic Name</label>
                            <input type="text" name="arabic_name" 
                            class="form-control" value="{{$vendingPoint->arabic_name}}">
                        </fieldset>
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community/Town</label>
                            <select name="community_town" id="communityTownPlace" 
                                class="selectpicker form-control" required>
                                @if($vendingPoint->community_id)
                                    <option disabled selected>Community</option>
                                @else @if($vendingPoint->town_id)
                                    <option disabled selected>Town</option>
                                @else
                                    <option disabled selected>Choose one...</option>
                                    <option value="community">Community</option> 
                                    <option value="town">Town</option>
                                @endif
                                @endif
                            </select>
                        </fieldset>
                        <div id="community_town_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Vending Point Place</label>
                            <select name="community_town_id" id="communityTownVendingPoint" 
                                class="selectpicker form-control" 
                                data-live-search="true">
                                @if($vendingPoint->community_id)
                                    <option disabled selected>{{$vendingPoint->Community->english_name}}</option>
                                    @foreach($communities as $community)
                                        <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                @else @if($vendingPoint->town_id)
                                    <option disabled selected>{{$vendingPoint->Town->english_name}}</option>
                                    @foreach($towns as $town)
                                        <option value="{{$town->id}}">{{$town->english_name}}</option>
                                    @endforeach
                                @endif
                                @endif
                            </select>
                        </fieldset>
                        <div id="community_town_id_error" style="color: red;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Region</label>
                            <select name="vendor_region_id" id="vendorRegion" 
                                class="selectpicker form-control" 
                                data-live-search="true">
                                @if($vendingPoint->vendor_region_id)
                                <option disabled selected>{{$vendingPoint->VendorRegion->english_name}}</option>
                                @foreach($vendorRegions as $vendorRegion)
                                <option value="{{$vendorRegion->id}}">{{$vendorRegion->english_name}}</option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($vendorRegions as $vendorRegion)
                                <option value="{{$vendorRegion->id}}">{{$vendorRegion->english_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                        <div id="vendor_region_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Username</label>
                            <select name="vendor_user_name_id" id="vendorUsername" 
                                class="selectpicker form-control" 
                                data-live-search="true">
                                @if($vendingPoint->vendor_username_id)
                                <option disabled selected>{{$vendingPoint->VendorUsername->name}}</option>
                                @foreach($vendorUsers as $vendorUser)
                                <option value="{{$vendorUser->id}}">{{$vendorUser->name}}</option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($vendorUsers as $vendorUser)
                                <option value="{{$vendorUser->id}}">{{$vendorUser->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                        <div id="vendor_user_name_id_error" style="color: red;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" 
                                value="{{$vendingPoint->phone_number}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Additional Phone Number</label>
                            <input type="text" name="additional_phone_number" class="form-control"
                                value="{{$vendingPoint->additional_phone_number}}">
                        </fieldset>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$vendingPoint->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h5>Served Communities</h5>
                </div>
                @if(count($vendorCommunities) > 0)

                    <table id="vendorCommunitiesTable" 
                        class="table table-striped data-table-vending-communitioes my-2">
                        <tbody>
                            @foreach($vendorCommunities as $vendorCommunitie)
                            <tr id="vendorCommunitieRow">
                                <td class="text-center">
                                    {{$vendorCommunitie->english_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteServedCommunity" id="deleteServedCommunity" 
                                        data-id="{{$vendorCommunitie->id}}">
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
                                <label class='col-md-12 control-label'>Add More Served Communities</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_community[]">
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
                                <label class='col-md-12 control-label'>Add Served Communities</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_community[]">
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

    $(document).on('change', '#communityTownPlace', function () {

        communityTown = $('#communityTownPlace').val();

        $.ajax({
            url: "/vendor/community_town/" + communityTown,
            method: 'GET',
            success: function(data) {

                var select = $('#communityTownVendingPoint');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });

    // delete served community
    $('#vendorCommunitiesTable').on('click', '.deleteServedCommunity',function() {
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
                    url: "{{ route('deleteServedCommunity') }}",
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