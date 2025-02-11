@extends('layouts/layoutMaster')

@section('title', 'communities camera')

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
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseCommunityCameraVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseHouseholdVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseCommunityCameraExport" aria-expanded="false" 
        aria-controls="collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseCommunityCameraVisualData collapseCommunityCameraExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseCommunityCameraVisualData">

</div>

<div class="collapse multi-collapse container mb-4" id="collapseCommunityCameraExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                            Export Installed Cameras Report
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearCameraFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div> 
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('camera.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select class="selectpicker form-control" 
                                        data-live-search="true" 
                                        name="sub_region" required>
                                        <option disabled selected>Choose Sub Region...</option>
                                        @foreach($subRegions as $subRegion)
                                        <option value="{{$subRegion->id}}">
                                            {{$subRegion->english_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div> 
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
                                    <input type="date" name="date" id="installationDate"
                                    class="form-control" title="Installation Data from"> 
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
All<span class="text-muted fw-light"> Installed Cameras</span> 
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
                        <label class='col-md-12 control-label'>Filter ByRegion</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="filterByRegion"
                            name="sub_region" required>
                            <option disabled selected>Choose Sub Region...</option>
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
                        <label class='col-md-12 control-label'>Installation date from</label>
                        <input type="date" class="form-control" name="date_from"
                        id="filterByDateFrom">
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
            @if(Auth::guard('user')->user()->user_type_id != 7 ||
                Auth::guard('user')->user()->user_type_id != 11 ||
                Auth::guard('user')->user()->user_type_id != 8 ||
                Auth::guard('user')->user()->user_type_id != 9)
                <div>
                    <p class="card-text">
                        <div>
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createCommunityCamera">
                                Create New One	
                            </button>
                        </div>
                        @include('services.camera.create')
                    </p>
                </div>
            @endif
            <table id="cameraCommunityTable" 
                class="table table-striped data-table-camera my-2">
                <thead>
                    <tr>
                        <th class="text-center">Community</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Responsible</th>
                        <th class="text-center"># of Cameras</th>
                        <th class="text-center">Installation date</th>
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

    var table;

    function DataTableContent() {

        table = $('.data-table-camera').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('camera.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.region_filter = $('#filterByRegion').val();
                    d.date_filter = $('#filterByDateFrom').val();
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'region', name: 'region'},
                {data: 'english_name', name: 'english_name'},
                {data: 'camera_number', name: 'camera_number'},
                {data: 'installation_date', name: 'installation_date'},
                {data: 'action' }
            ]
        }); 
    }

    $(function () {

        DataTableContent();

        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByDateFrom').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDateFrom').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-camera')) {
                $('.data-table-camera').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearCameraFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#installationDate').val(' ');
        });
                
        // Edit details
        $('#cameraCommunityTable').on('click', '.updateCameraCommunity',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'displaced-household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url); 
                }
            });
        });

        // View record details
        $('#cameraCommunityTable').on('click', '.viewCameraCommunityButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // Delete record
        $('#cameraCommunityTable').on('click', '.deleteCameraCommunity', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this displaced household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCameraCommunity') }}",
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
                                    $('#cameraCommunityTable').DataTable().draw();
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