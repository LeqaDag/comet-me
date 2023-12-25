@extends('layouts/layoutMaster')

@section('title', 'displaced households')

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
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseDisplacedHouseholdVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseHouseholdVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseDisplacedHouseholdExport" aria-expanded="false" 
        aria-controls="collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseDisplacedHouseholdVisualData collapseDisplacedHouseholdExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseDisplacedHouseholdVisualData">

</div>

<div class="collapse multi-collapse container mb-4" id="collapseDisplacedHouseholdExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Export Household Report
                        <i class='fa-solid fa-file-excel text-info'></i>
                    </h5>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('displaced-household.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="area" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Search Area</option>
                                        <option value="A">Area A</option>
                                        <option value="B">Area B</option>
                                        <option value="C">Area C</option>
                                    </select> 
                                </fieldset>
                            </div>
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
                                        <option disabled selected>Search Old Community</option>
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
                                    <input type="date" name="date" 
                                    class="form-control" title="Displacement Data from"> 
                                </fieldset>
                            </div>
                            <br><br><br>
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
All<span class="text-muted fw-light"> Displaced Families</span> 
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
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id != 7 ||
                Auth::guard('user')->user()->user_type_id != 11  )
                <div>
                    <p class="card-text">
                        <div>
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createDisplacedHousehold">
                                Create New Displaced Families	
                            </button>
                        </div>
                        @include('employee.household.displaced.create')
                    </p>
                </div>
            @endif
            <table id="displacedHouseholdsTable" 
                class="table table-striped data-table-displaced-household my-2">
                <thead>
                    <tr>
                        <th class="text-center">Household Name</th>
                        <th class="text-center">Old Community</th>
                        <th class="text-center">New Region</th>
                        <th class="text-center">New Community</th>
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

        var table = $('.data-table-displaced-household').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('displaced-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'old_community', name: 'old_community'},
                {data: 'region', name: 'region'},
                {data: 'new_community', name: 'new_community'},
                {data: 'action' }
            ]
        }); 
         
        // Edit details
        $('#displacedHouseholdsTable').on('click', '.updateDisplacedHousehold',function() {
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
        $('#displacedHouseholdsTable').on('click', '.viewDisplacedHouseholdButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });

        // Delete record
        $('#displacedHouseholdsTable').on('click', '.deleteDisplacedHousehold', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this displaced household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteDisplacedHousehold') }}",
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
                                    $('#displacedHouseholdsTable').DataTable().draw();
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