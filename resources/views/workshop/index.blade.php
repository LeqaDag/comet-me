@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'workshops')


@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseAllWorkshopsExport" aria-expanded="false" 
        aria-controls="collapseAllWorkshopsExport"> 
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p> 

<div class="collapse multi-collapse container mb-4" id="collapseAllWorkshopsExport">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export All Workshops Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearAllWorkshopsFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('all-workshop.export') }}">
                        @csrf 
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($communities as $community)
                                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Workshop Type</label>
                                        <select name="workshop_type_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($workshopTypes as $workshopType)
                                                <option value="{{$workshopType->id}}">{{$workshopType->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Workshop Date</label>
                                        <input type="date" name="completed_date" class="form-control"
                                            id="filterByCompletedDateExport">
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Download Excel</label>
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div> 
    </div> 
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Workshops
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
                        <label class='col-md-12 control-label'>Filter By Workshop Type</label>
                        <select name="workshop_type_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByWorkshopType">
                            <option disabled selected>Choose one...</option>
                            @foreach($workshopTypes as $workshopType)
                                <option value="{{$workshopType->id}}">{{$workshopType->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Workshop Date (from)</label>
                        <input type="date" name="completed_date" class="form-control"
                            id="filterByDate">
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3" >
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

        <hr>
        <div class="card-body">

            <h5 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">Import </span>Workshop Details
            </h5>

            <div class="row">
                <form action="{{route('all-workshop.import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                        @error('excel_file')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div> <br>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button id="workshopsImportButton" type="submit" class="btn btn-success btn-block">
                            <i class='fa-solid fa-upload'></i>
                            Proccess
                        </button>
                    </div>
                </form>
            </div>
            <br>
            <table id="allWorkshopTable" 
                class="table table-striped data-table-all-workshop my-2">
                <thead>
                    <tr>
                        <th class="text-center">Community</th>
                        <th class="text-center">Compound</th>
                        <th class="text-center">Household</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Lead By</th>
                        <th class="text-center">Co-Trainers</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('workshop.show')

<script type="text/javascript">
    
    var table;
    function DataTableContent() {

        table = $('.data-table-all-workshop').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-workshop.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.type_filter = $('#filterByWorkshopType').val();
                    d.date_filter = $('#filterByDate').val();
                }
            },
            columns: [
                {data: 'community_name', name: 'community_name'},
                {data: 'compound', name: 'compound'},
                {data: 'household', name: 'household'},
                {data: 'workshop_type', name: 'workshop_type'},
                {data: 'date', name: 'date'},
                {data: 'lead_user_name', name: 'lead_user_name'},
                {data: 'co_trainer', name: 'co_trainer'},
                {data: 'action' }
            ]
        });
    }

    // Clear Filters for Export
    $('#clearAllWorkshopsFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#filterByCompletedDateExport').val(' ');
    });

    $(function () {

        var urlParams = new URLSearchParams(window.location.search);
        var filterByCommunity = urlParams.get('filterByCommunity');

        if (filterByCommunity) {

            $('#filterByCommunity').val(filterByCommunity);
        }

        DataTableContent();
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByWorkshopType').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByDate').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDate').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-all-workshop')) {
                $('.data-table-all-workshop').DataTable().destroy();
            }
            DataTableContent();
        });

        // View record details
        $('#allWorkshopTable').on('click', '.viewAllWorkshops',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'all-workshop/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#allWorkshopModalTitle').html(" ");
                    $('#allWorkshopModalTitle').html(response['community'].english_name);

                    $('#workshopType').html(" ");
                    $('#workshopType').html(response['workshopType'].english_name);
                    
                    $('#workshopCommunity').html(" ");
                    $('#workshopCommunity').html(response['community'].english_name);

                    $('#workshopAdult').html(" ");
                    $('#workshopAdult').html(response['allWorkshop'].number_of_youth);

                    $('#workshopMale').html(" ");
                    $('#workshopMale').html(response['allWorkshop'].number_of_male);

                    $('#workshopFemale').html(" ");
                    $('#workshopFemale').html(response['allWorkshop'].number_of_female);

                    $('#workshopDate').html(" ");
                    $('#workshopDate').html(response['allWorkshop'].date);

                    $('#workshopHours').html(" ");
                    $('#workshopHours').html(response['allWorkshop'].number_of_hours);

                    $('#workshopLeadBy').html(" ");
                    $('#workshopLeadBy').html(response['leadBy'].name);

                    $('#workshopLawyer').html('');
                    $('#workshopLawyer').html(response['allWorkshop'].lawyer);

                    $('#workshopNotes').html('');
                    $('#workshopNotes').html(response['allWorkshop'].notes);

                    $('#workshopStories').html('');
                    $('#workshopStories').html(response['allWorkshop'].stories);

                    $('#workshopCoTrainers').html('');
                    if(response['coTrainers']) {
                        for (var i = 0; i < response['coTrainers'].length; i++) {
                            $("#workshopCoTrainers").append(
                                '<ul><li>'+ response['coTrainers'][i].name + '</li> </ul>'
                            );
                        }
                    }

                    $('#workshopHousehold').html('');
                    if(response['household']) $('#workshopHousehold').html(response['household'].english_name);
                    

                    $('#workshopPhotos').html('');

                    // Check if photos exist
                    if (response['workshopCommunityPhotos'].length > 0) {
                        response['workshopCommunityPhotos'].forEach(function(photo) {
                            let photoUrl = '/workshops/' + photo.name; // adjust if needed

                            let imgElement = `
                                <img src="${photoUrl}" 
                                    class="img-thumbnail me-2 mb-2" 
                                    style="max-width: 250px;" 
                                    alt="Workshop Photo">
                            `;

                            $('#workshopPhotos').append(imgElement);
                        });
                    } else {
                        $('#workshopPhotos').html('<p>No photos available.</p>');
                    }
                }
            });
        });

        // View record
        $('#allWorkshopTable').on('click', '.updateAllWorkshops',function() {
            
            var id = $(this).data('id');
            var url = window.location.href; 
            
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });
    });

</script>
@endsection