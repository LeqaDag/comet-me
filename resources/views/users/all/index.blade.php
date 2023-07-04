@extends('layouts/layoutMaster')

@section('title', 'all users')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Active Users
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
        <!-- <div class="card-header">
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('all-active.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="region"
                                class="form-control">
                                <option disabled selected>Search Region</option>
                                @foreach($regions as $region)
                                <option value="{{$region->english_name}}">
                                    {{$region->english_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community" 
                                class="form-control">
                                <option disabled selected>Search Community</option>
                                @foreach($communities as $community)
                                <option value="{{$community->english_name}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="system_type"
                                class="form-control">
                                <option disabled selected>Search System Type</option>
                                @foreach($energySystemTypes as $energySystemType)
                                    <option value="{{$energySystemType->name}}">
                                        {{$energySystemType->name}}
                                    </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button class="btn btn-info" type="submit">
                            <i class='fa-solid fa-file-excel'></i>
                            Export Excel
                        </button>
                    </div>
                </div> 
            </form>
        </div> -->
        <div class="card-body">
            <table id="allActiveUsersTable" class="table table-striped data-table-all-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Energy Service</th>
                        <th class="text-center">Water Service</th>
                        <th class="text-center">Internet Service</th>
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

        var table = $('.data-table-all-users').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('all-active.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [ 
                {data: 'household_name', name: 'household_name'},
                {data: 'region', name: 'region'},
                {data: 'community_name', name: 'community_name'},
                {data: 'energy_system_status', name: 'energy_system_status'},
                {data: 'water_system_status', name: 'water_system_status'},
                {data: 'internet_system_status', name: 'internet_system_status'},
            ]
        });
        
    });

   
</script>
@endsection