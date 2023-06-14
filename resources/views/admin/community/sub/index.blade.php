@extends('layouts/layoutMaster')

@section('title', 'sub communities')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> sub communities
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
            <div class="card-header">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('sub-community-household.export') }}">
                    @csrf 
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <fieldset class="form-group">
                                <select name="community" class="form-control">
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
                                <select name="region" class="form-control">
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
                                <select name="system_type"  class="form-control">
                                    <option disabled selected>Search System Type</option>
                                    @foreach($energySystemTypes as $energySystemType)
                                        <option value="{{$energySystemType->id}}">
                                            {{$energySystemType->name}}
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
                </form>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createSubCommunity">
                        Create New Sub Community	
                    </button>
                    @include('admin.community.sub.create_sub')
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createSubCommunityHousehold">
                        Create New Sub Community Household	
                    </button>
                    @include('admin.community.sub.create')
                </div>
            </div>

            <table id="subCommunityTable" class="table table-striped data-table-sub-communities my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Household</th>
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

        var table = $('.data-table-sub-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sub-community-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'community_english_name', name: 'community_english_name'},
                {data: 'name', name: 'name'},
                {data: 'household', name: 'household'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#subCommunityTable').on('click','.detailsSubCommunityButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'sub-community-household/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#communityModalTitle').html(" ");
                    $('#englishNameCommunity').html(" ");
                    $('#arabicNameCommunity').html(" ");
                    $('#numberOfCompoundsCommunity').html(" ");
                    $('#numberOfPeopleCommunity').html(" ");
                    $('#englishNameRegion').html(" ");
                    $('#numberOfHouseholdCommunity').html(" ");
                    $('#englishNameSubRegion').html(" ");
                    $('#statusCommunity').html(" ");
                    $('#energyServiceCommunity').html(" ");
                    $('#energyServiceYearCommunity').html(" ");
                    $('#waterServiceCommunity').html(" ");
                    $('#waterServiceYearCommunity').html(" ");
                    $('#internetServiceCommunity').html(" ");
                    $('#internetServiceYearCommunity').html(" ");

                    $('#communityModalTitle').html(response['community'].english_name);
                    $('#englishNameCommunity').html(response['community'].english_name);
                    $('#arabicNameCommunity').html(response['community'].arabic_name);
                    $('#numberOfCompoundsCommunity').html(response['community'].number_of_compound);
                    $('#numberOfPeopleCommunity').html(response['community'].number_of_people);
                    $('#englishNameRegion').html(response['region'].english_name);
                    $('#numberOfHouseholdCommunity').html(response['community'].number_of_households);
                    $('#englishNameSubRegion').html(response['sub-region'].english_name);
                    $('#statusCommunity').html(response['status'].name);
                    $('#energyServiceCommunity').html(response['community'].energy_service);
                    $('#energyServiceYearCommunity').html(response['community'].energy_service_beginning_year);
                    $('#waterServiceCommunity').html(response['community'].water_service);
                    $('#waterServiceYearCommunity').html(response['community'].water_service_beginning_year);
                    $('#internetServiceCommunity').html(response['community'].internet_service);
                    $('#internetServiceYearCommunity').html(response['community'].internet_service_beginning_year);

                    $("#waterSourcesCommunity").html(" ");
                    for (var i = 0; i < response['communityWaterSources'].length; i++) {
                        $("#waterSourcesCommunity").append(
                            '<ul><li>'+ response['communityWaterSources'][i].name +'</li> </ul>');
                    }
                }
            });
        });

        // View record update page
        $('#subCommunityTable').on('click', '.updateSubCommunity', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'community/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // delete community
        $('#subCommunityTable').on('click', '.deleteSubCommunityHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this sub community household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteSubCommunityHousehold') }}",
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
                                    $('#subCommunityTable').DataTable().draw();
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