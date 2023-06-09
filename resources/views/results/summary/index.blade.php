@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water summary')

@include('layouts.all')

@section('content')


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Summary </span> of regular monitoring program
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
        
        </div>
        <div class="card-body">
            <table id="waterResultSummaryTable" 
                class="table table-striped data-table-water-summary my-2">
                <thead>
                    <tr>
                        <th class="text-center">Community</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Total Samples</th>
                        <th class="text-center">CFU > 10</th>
                        <th class="text-center">CFU < 10</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr class="summaryRow">
                            <td class="text-center">
                                {{$result->community_name}}
                            </td>
                            <td class="text-center">
                                {{$result->year}}
                            </td>
                            <td class="text-center">
                                {{$result->samples}}
                            </td>
                            <td class="cfuMax{{$result->id}} text-center" id="cfuMax" data-id="{{$result->community_id}}"
                                data-class="{{$result->year}}" data-name="{{$result->id}}">
                                <script type="text/javascript">
                                    <?php $count = 0; ?>
                                    id = $(".summaryRow #cfuMax").data("id");
                                    year = $(".summaryRow #cfuMax").data("class");
                                    result = $(".summaryRow #cfuMax").data("name");
                                  
                                    // AJAX request
                                    $.ajax({
                                        url: 'quality-result/cfu/max/' + id + "/"+ year,
                                        type: 'get',
                                        dataType: 'json',
                                        success: function(response) {
                                            console.log(response);
                                           // $(".cfuMax"+ result).append(response);
                                        }
                                    });
                                </script>
                            </td>
                            <td class="text-center" id="cfuMin">
                             
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection