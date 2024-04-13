@extends('layouts/layoutMaster')

@section('title', 'energy generator')

@include('layouts.all')

@section('content')


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Energy Generator - Communities 
</h4>
 
@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

<div class="container mb-4 ">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 7 ||
                    Auth::guard('user')->user()->user_type_id == 4 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createGeneratorEnergy">
                            Create New Energy Generator Community	
                        </button>
                        @include('users.energy.maintenance.generator.create')
                    </div>
                @endif
            </div>

            <table id="generatorEnergyTable" class="table table-striped data-table-energy-generator my-2">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Community</th>
                        <th>Generator</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> 


<!-- <br>
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Energy Turbines - Communities 
</h4>
 
@if(session()->has('success_message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    </div>
@endif


<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 7 ||
                    Auth::guard('user')->user()->user_type_id == 4 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createTurbineEnergy">
                            Create New Energy Turbine Community	
                        </button>
                        @include('users.energy.maintenance.turbine.create')
                    </div>
                @endif
            </div>

            <table id="turbineEnergyTable" class="table table-striped data-table-energy-turbine my-2">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Community</th>
                        <th>Turbine</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>  -->

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-energy-generator').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('energy-generator-turbine.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'english_name', name: 'english_name'},
                {data: 'generator_model', name: 'generator_model'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#generatorEnergyTable').on('click', '.deleteGenerator',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Generator?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyGenerator') }}",
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
                                $('#generatorEnergyTable').DataTable().draw();
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

    $(function () {

        var table1 = $('.data-table-turbine-turbine').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('energy-turbine.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'turbine_name', name: 'turbine_name'},
                {data: 'community', name: 'community'},
                {data: 'model', name: 'model'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#turbineEnergyTable').on('click', '.deleteTurbine',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Turbine?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyTurbine') }}",
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
                                $('#turbineEnergyTable').DataTable().draw();
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

</script>
@endsection