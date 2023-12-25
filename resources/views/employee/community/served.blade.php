@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'served communities')

@include('layouts.all')

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    @if ($communityRecords)
        {{$communityRecords}}
    @endif
  <span class="text-muted fw-light">Served </span> communities
</h4>

@include('employee.community.details')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <table id="communityServedTable" 
                class="table table-striped data-table-served-communities my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center"># of Households</th>
                        <th class="text-center"># of People</th>
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
    
        var table = $('.data-table-served-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('served-community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'number_of_household', name: 'number_of_household'},
                {data: 'number_of_people', name: 'number_of_people'},
                {data: 'name', name: 'name'},
                {data: 'subname', name: 'subname'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#communityServedTable').on('click', '.detailsCommunityButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            var updatedURL = url.replace('/served-community', '/community');

            updatedURL = updatedURL +'/'+ id ;
            window.open(updatedURL); 
        });
        
    });
</script>
@endsection