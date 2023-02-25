@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'initial communities')

@include('layouts.all')

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    @if ($communityRecords)
        {{$communityRecords}}
    @endif
  <span class="text-muted fw-light">Initial </span> communities
</h4>


@include('employee.community.details')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <p class="card-text">
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunity">
                        Create New Community	
                    </button>
                    @include('employee.community.create')
                </div>
            </p>
            <table id="communityInitialTable" 
                class="table table-striped data-table-initial-communities my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center"># of Households</th>
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
    
        var table = $('.data-table-initial-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('initial-community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'number_of_people', name: 'number_of_people'},
                {data: 'name', name: 'name'},
                {data: 'subname', name: 'subname'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#communityInitialTable').on('click','.detailsCommunityButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'community/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

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
                    
                    for (var i = 0; i < response['public'].length; i++) {
                        $("#structuresCommunity").append(
                            '<ul><li>'+ response['public'][i].english_name +'</li> </ul>');
                    } 
                }
            });
        });
        
    });
</script>
@endsection