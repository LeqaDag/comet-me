
@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Sub-Sub-Regions
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createSubSubRegionModal">
                    Create New Sub-Sub-Region	
                </button>
                @include('regions.sub_sub_regions.create')
            </div>
            <table id="subSubRegionTable" class="table table-striped data-table-sub-regions my-2">
                <thead>
                    <tr>
                        <th class="text-center">Sub Region</th>
                        <th class="text-center">Region</th>
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

        // DataTable
        var table = $('.data-table-sub-regions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sub-sub-region.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'name', name: 'name'},
                { data: 'action' }
            ],
        });
    });
</script>
@endsection