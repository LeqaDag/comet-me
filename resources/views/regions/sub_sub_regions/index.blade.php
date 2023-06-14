
@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Sub-Sub-Regions
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('regions.sub_sub_regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createSubSubRegionModal">
                    Create New Sub-Sub-Region	
                </button>
                @include('regions.sub_sub_regions.create')
            </div>
            <table id="subSubRegionTable" class="table table-striped data-table-sub-regions my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Sub Region</th>
                        <th class="text-center">Options</th>
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
        // DataTable
        var table = $('.data-table-sub-regions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sub-sub-region.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'region', name: 'region'},
                {data: 'name', name: 'name'},
                { data: 'action' }
            ],
        });
    });

    var id, region_id = 0, sub_region_id = 0;
    // Update record
    $('#subSubRegionTable').on('click', '.updateSubSubRegion',function() {
        id = $(this).data('id');

        // AJAX request
        $.ajax({
            url: 'getSubSubRegionData/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {

                if(response.success == 1) {

                    $('#english_name').val(response.english_name);
                    $('#arabic_name').val(response.arabic_name);

                    // get region by id
                    $.ajax({
                        url: 'getRegionData/' + response.region_id,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {

                            if(response.success == 1) {

                                $('#selectedRegion').text(response.english_name);
                                $('#selectedRegionValue').val(response.id);
                                $.ajax({
                                    url: 'getAllSubRegion/',
                                    type: 'get',
                                    dataType: 'json',
                                    success: function(response) {
                                        $("#updateRegionId").html(" ");
                                        if(response.success == 1) {
                                            $("#updateRegionId").html(" ");
                                            response.regions.forEach(el => {
                                                $("#updateRegionId").append(`<option value='${el.id}'> ${el.english_name}</option>`)

                                            });
                                        };
                                    }
                                });
                                
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });

                    // get sub region by id
                    $.ajax({
                        url: 'getSubRegionData/' + response.sub_region_id,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {

                            if(response.success == 1) {
                                
                                $('#selectedSubRegion').html(" ");
                                $('#selectedSubRegion').text(response.english_name);
                                $('#selectedSubRegionValue').val(response.id);

                                $.ajax({
                                    url: 'getAllSubSubRegion/',
                                    type: 'get',
                                    dataType: 'json',
                                    success: function(response) {
                                        $("#updateSubRegionId").html(" ");
                                        if(response.success == 1) {
                                            response.subRegions.forEach(el => {
                                                $(".updateSubRegionId").append(`<option value='${el.id}'> ${el.english_name}</option>`)

                                            });
                                        };
                                    }
                                });
                                
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });

                    region_id = $("#selectedRegionValue").val();
                    
                    $(document).on('change', '#updateRegionId', function () {

                        region_id = $(this).val();
                    });

                    sub_region_id = $("#selectedSubRegionValue").val();
                    
                    $(document).on('change', '#updateSubRegionId', function () {

                        sub_region_id = $(this).val();
                    });
                }
            }
        });
    });

    $('#btnSaveSubRegion').on('click', function() {
                    
        english_name = $('#english_name').val();
        arabic_name = $('#arabic_name').val();

        $.ajax({
            url: 'sub-sub-region/edit_data/' + id,
            type: 'get',
            data: {
                id: id,
                english_name: english_name,
                arabic_name: arabic_name,
                region_id: region_id,
                sub_region_id: sub_region_id
            },
            dataType: 'json',
            success: function(response) {

                $('#updateSubSubRegionModal').modal('toggle');
                $('#closeSubSubRegionUpdate').click ();

                if(response == 1) {
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Sub-Sub Region Updated Successfully!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay!'
                    }).then((result) => {

                        $('#subSubRegionTable').DataTable().draw();
                    });
                }
            }
        });
    });
    
    // Delete record
    $('#subSubRegionTable').on('click', '.deleteSubSubRegion',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this sub-sub region?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteSubSubRegion') }}",
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
                                $('#subSubRegionTable').DataTable().draw();
                            });
                        } else {

                            alert("Invalid ID.");
                        }
                    }
                });
            }  else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });
</script>
@endsection