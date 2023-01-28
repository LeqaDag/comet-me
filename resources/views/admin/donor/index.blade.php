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
<link rel="stylesheet" 
href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}"/>
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
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/tables-datatables-advanced.js')}}"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js" async defer></script>
<script src="{{asset('assets/js/community/charts.blade.php')}}"></script>
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> donors
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <p class="card-text">
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createDonor">
                        Create New Donor	
                    </button>
                    @include('admin.donor.create')

                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunityDonor">
                        Create New Community Donor	
                    </button>
                    @include('admin.donor.community.create')

                </div>
            </p>
        </div>
        <div class="table-responsive">
            @if (count($donors))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($donors as $donor)
                    @if($donor->is_archived == 0)
                        <tr> 
                            <td class="text-center">
                                @if($donor->donor_name == "0") 
                                    Not yet attributed
                                @else
                                    {{ $donor->donor_name }}
                                @endif
                            </td>
                            <td class="text-center">
                                <a data-bs-target="#donorCommunity{{$donor->id}}"
                                   type="button" data-bs-toggle="modal" title="View Communities">
                                    <i class="fas fa-building" style="color:blue;"></i>
                                </a>
                                @include('admin.donor.community')
                                <a href="">
                                    <i class="fas fa-edit" style="color:green;"></i>
                                </a>
                                <a href="{{ url('donor/destory', $donor->id) }}"
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
                    {!! $donors->links('pagination::bootstrap-4') !!}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
