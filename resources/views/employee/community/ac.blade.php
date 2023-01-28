@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'communities')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset("assets/vendor/libs/cleavejs/cleave-phone.js")}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/tables-datatables-advanced.js')}}"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js" async defer></script>
@endsection


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    @if ($communityRecords)
        {{$communityRecords}}
    @endif
    <span class="text-muted fw-light">AC </span> communities
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <p class="card-text">
               
            </p>
        </div>
        <div class="table-responsive">
            @if (count($communities))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">English Name</th>
                            <th class="text-center">Arabic Name</th>
                            <th class="text-center"># of Households</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Sub Region</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($communities as $community)
                    @if($community->is_archived == 0)
                        <tr> 
                            <td class="text-center">
                                <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityDetails{{$community->id}}">
                                    <i class="fas fa-eye" style="color:blue;"></i>
                                </a>
                            </td>
                            @include('employee.community.details')
                            <td class="text-center">
                                {{ $community->english_name }}
                            </td>
                            <td class="text-center">
                                {{ $community->arabic_name }}
                            </td>
                            <td class="text-center">
                                {{ $community->number_of_people }}
                            </td>
                            <td class="text-center">
                                {{ $community->Region->english_name }} 
                            </td>
                            <td class="text-center">
                                @if($community->SubRegion)
                                {{ $community->SubRegion->english_name }} 
                                @endif
                            </td>
                            <td class="text-center">
                                <a data-bs-target="#communityImage{{$community->id}}"
                                   type="button" data-bs-toggle="modal" title="add images">
                                    <i class="fas fa-image" style="color:blue;"></i>
                                </a>
                                @include('employee.community.image')

                                <a href="">
                                    <i class="fas fa-edit" style="color:green;"></i>
                                </a>
                                <a href="{{ url('community/destory', $community->id) }}"
                                    title="delete">
                                    <i class="fas fa-trash-alt delete-item"
                                    style="color:red;"></i>
                                    {{ method_field('delete') }} 
                                </a>
                            </td>
                        </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {!! $communities->links('pagination::bootstrap-4') !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection