@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

@include('layouts.all')

@section('content')

<h5 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Summary /</span> Region and Sub-region
</h5>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <fieldset class="form-group">
                <label class='col-md-12 control-label'>Region</label>
                <select name="region_id" id="selectedRegion" 
                    class="form-control" required>
                    <option disabled selected>Choose one...</option>
                    @foreach($regions as $region)
                    <option value="{{$region->id}}">
                        {{$region->english_name}}
                    </option>
                    @endforeach
                </select>
            </fieldset>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <fieldset class="form-group">
                <label class='col-md-12 control-label'>Sub Region</label>
                <select name="sub_region_id" id="selectedSubRegions" 
                class="form-control" disabled required>
                    <option disabled selected>Choose one...</option>
                </select>
            </fieldset>
        </div>
    </div>
</div>

<div class="card mb-4" id="regionsDivDetails" style="visiblity:hidden; display:none">
    <div class="card-body">
        <div class="row"> 
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="communitiesNumber">#</h4>
                        <span class="d-block mb-4 text-nowrap">Communities</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-home me-1 bx-lg text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="householdsNumber">#</h4>
                        <span class="d-block mb-4 text-nowrap">Households</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-user me-1 bx-lg text-warning"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="energyUsersNumber"></h4>
                        <span class="d-block mb-4 text-nowrap">Energy Holders</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-user-check me-1 bx-lg text-danger"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="mgSystemsNumber">
                            
                        </h4>
                        <span class="d-block mb-4 text-nowrap">MG Systems</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-grid me-1 bx-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="fbsSystemsNumber"></h4>
                        <span class="d-block mb-4 text-nowrap">FBS Systems</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-sun me-1 bx-lg text-dark"></i>
                    </div>
                </div> 
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="h2oUsersNumber"></h4>
                        <span class="d-block mb-4 text-nowrap">H2O Holders</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-droplet me-1 bx-lg text-info"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="internetUsersNumber"></h4>
                        <span class="d-block mb-4 text-nowrap">Internet Holders</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-wifi me-1 bx-lg text-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Regions
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div> 
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2  )
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createRegionModal">
                    Create New Region	
                </button>
                @include('regions.create')
            @endif
            </div>
            <table id="regionsTable" class="table table-striped data-table-regions my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                                Auth::guard('user')->user()->user_type_id == 2  )
                               
                            <th>Options</th>
                        @else
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        $(document).on('change', '#selectedRegion', function () {
            region_id = $(this).val(); 
    
            $.ajax({ 
                url: "region/get_region/" + region_id,
                method: 'GET',
                success: function(data) {
                    $('#selectedSubRegions').prop('disabled', false);
                    $('#selectedSubRegions').html(data.html);

                    $(document).on('change', '#selectedSubRegions', function () {
                        sub_region_id = $(this).val();
                
                        $.ajax({
                            url: "region/get_sub_region/" + region_id + "/" + sub_region_id,
                            method: 'GET',
                            success: function(data) {
                               
                                $("#regionsDivDetails").css("visibility", "visible");
                                $("#regionsDivDetails").css('display', 'block');
                                $("#communitiesNumber").html(data.countCommunities);
                                $("#householdsNumber").html(data.countHouseholds);
                                $("#energyUsersNumber").html(data.countEnergyUsers);
                                $("#h2oUsersNumber").html(data.countH2oUsers);
                                $("#fbsSystemsNumber").html(data.countFbsSystem);
                                $("#mgSystemsNumber").html(data.countMgSystem);
                                $("#internetUsersNumber").html(data.countInternetUsers);
                            }
                        });

                    });
                    
                }
            });

        });

        // DataTable
        var table = $('.data-table-regions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('region.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'action' }
            ],
        });

        var id = 0;
 
        // Update record
        $('#regionsTable').on('click','.updateRegion',function() {
            id = $(this).data('id');

            // AJAX request
            $.ajax({
                url: 'getRegionData/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    
                    $('#english_name_region').val(response.english_name);
                    $('#arabic_name_region').val(response.arabic_name);
                }
            });
        });

        $('#saveRegionButton').on('click', function() {
                        
            english_name = $('#english_name_region').val();
            arabic_name = $('#arabic_name_region').val();

            $.ajax({
                url: 'region/edit_region/' + id,
                type: 'get',
                data: {
                    id: id,
                    english_name: english_name,
                    arabic_name: arabic_name
                }, 
                dataType: 'json',
                success: function(response) {

                    $('#updateRegionModal').modal('toggle');
                    $('#closeRegionUpdate').click ();

                    if(response == 1) {
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Region Updated Successfully!',
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {

                            $('#regionsTable').DataTable().draw();
                        });
                    }
                }
            });
        });
        
        // Delete record
        $('#regionsTable').on('click', '.deleteRegion',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this region?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteRegion') }}",
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
                                    $('#regionsTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
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