@extends('layouts/layoutMaster')

@section('title', 'community compounds')

@include('layouts.all')

@section('content')
<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>


<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseCompoundCommunityExport" aria-expanded="false" 
        aria-controls="collapseCompoundCommunityExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p>

<div class="collapse multi-collapse container mb-4" id="collapseCompoundCommunityExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                                Export Community-Compound Report 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearCompoundCommunityFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('community-compound.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="community" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="region" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Region</option>
                                        @foreach($regions as $region)
                                        <option value="{{$region->id}}">
                                            {{$region->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="compound" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Compound</option>
                                        @foreach($compounds as $compound)
                                        <option value="{{$compound->id}}">
                                            {{$compound->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>  
        </div>
    </div> 
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> community compounds
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Community</label>
                        <select name="community_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByCommunity">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Region</label>
                        <select name="region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($regions as $region)
                                <option value="{{$region->id}}">{{$region->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Sub Region</label>
                        <select name="sub_region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterBySubRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($subregions as $subRegion)
                                <option value="{{$subRegion->id}}">{{$subRegion->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-body">

            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2  )
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunityCompound">
                        Create New Community Compound	
                    </button>
                    @include('admin.community.compound.create_compound')
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCompoundHouseholds">
                        Create New Compound Households	
                    </button>
                    @include('admin.community.compound.create')
                </div>
            </div>
            @endif
            
            <table id="compoundCommunityTable" class="table table-striped data-table-compound-communities my-2">
                <thead>
                    <tr>
                        <th >Household</th>
                        <th >Community</th>
                        <th >Region</th>
                        <th >Community Compound</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                            Auth::guard('user')->user()->user_type_id == 2  )
                            <th >Options</th>
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

    var table;
    function DataTableContent() {
        table = $('.data-table-compound-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('community-compound.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByCommunity').val();
                    d.second_filter = $('#filterByRegion').val();
                    d.third_filter = $('#filterBySubRegion').val();
                }
            },
            columns: [
                {data: 'household', name: 'household'},
                {data: 'community_english_name', name: 'community_english_name'},
                {data: 'name', name: 'name'},
                {data: 'english_name', name: 'english_name'},
                {data: 'action'}
            ]
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterBySubRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-compound-communities')) {
                $('.data-table-compound-communities').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearCompoundCommunityFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
        });

        // View record update page
        $('#compoundCommunityTable').on('click', '.updateCompoundCommunityHousehold', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'community-compound/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // delete community
        $('#compoundCommunityTable').on('click', '.deleteCompoundHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this sub compound household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCompoundHousehold') }}",
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
                                    $('#compoundCommunityTable').DataTable().draw();
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