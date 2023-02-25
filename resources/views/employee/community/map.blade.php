@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'map communities')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">{{$community->english_name}} </span> Location
</h4>

    
<div class="container">
    <div class="card my-2">
        <div class="card-body"> 
            <div class="row">
                <div class="col-md">
                    <div class="row">
                        {!!html_entity_decode($community->location_gis)!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection