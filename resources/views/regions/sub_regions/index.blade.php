@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Sub-Regions
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('regions.sub_regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2  )
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createSubRegionModal">
                    Create New Sub-Region	
                </button>
                @include('regions.create-sub')
            @endif
            </div>
            <table id="subRegionTable" class="table table-striped data-table-sub-regions my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th> 
                        <th>Region</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                            Auth::guard('user')->user()->user_type_id == 2  )
                            <th>Options</th>
                        @else
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <input type="hidden" name="txtSubRegionId" id="txtSubRegionId" value="0">
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
                url: "{{ route('sub-region.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                { data: 'action' }
            ],
            
        });
        var id, region_id = 0;
        // Update record
        $('#subRegionTable').on('click','.updateSubRegion',function() {
            id = $(this).data('id');

            $('#txtSubRegionId').val(id);

            // AJAX request
            $.ajax({
                url: 'getSubRegionData/' + id,
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
                                                response.regions.forEach(el => {
                                                    $(".updateRegionId").append(`<option value='${el.id}'> ${el.english_name}</option>`)
    
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
                    }
                }
            });
        });

        $('#btnSaveSubRegion').on('click', function() {
                        
            english_name = $('#english_name').val();
            arabic_name = $('#arabic_name').val();

            $.ajax({
                url: 'sub-region/edit_sub_region/' + id,
                type: 'get',
                data: {
                    id: id,
                    english_name: english_name,
                    arabic_name: arabic_name,
                    region_id: region_id
                },
                dataType: 'json',
                success: function(response) {

                    $('#updateSubRegionModal').modal('toggle');
                    $('#closeSubRegionUpdate').click ();

                    if(response == 1) {
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Sub Region Updated Successfully!',
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {

                            $('#subRegionTable').DataTable().draw();
                        });
                    }
                }
            });
        });
        
        // Delete record
        $('#subRegionTable').on('click', '.deleteSubRegion',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this sub region?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteSubRegion') }}",
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
                                    $('#subRegionTable').DataTable().draw();
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
    });
</script>

@endsection