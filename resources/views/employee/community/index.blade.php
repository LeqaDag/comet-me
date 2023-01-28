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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<!-- JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

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
  <span class="text-muted fw-light">All </span> communities
</h4>

<!-- Donut Chart -->
<div class="col-md-6 col-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Expense Ratio</h5>
                <small class="text-muted">Spending on various categories</small>
            </div>
            <div class="dropdown d-none d-sm-flex">
                <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-calendar"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="javascript:void(0);" 
                            class="dropdown-item d-flex align-items-center">
                            Today
                        </a>
                    </li>
                    <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                    <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                    <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                    <li>
                    <hr class="dropdown-divider">
                    </li>
                    <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                    <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div id="donutChart"></div>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Energy Service</h5>
        </div>
        <div class="card-body">
            <ul class="p-0 m-0">
                <li class="d-flex mb-4 pb-2">
                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityInitial">
                                <i class='bx bx-message'></i>
                            </a>
                        </span>
                        @include('employee.community.service.initial')
                    </div>
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Initial Communities</span>
                            <span class="text-muted">{{$communityInitial}}</span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width: {{$communityInitial}}%" 
                                role="progressbar" aria-valuenow="{{$communityInitial}}" 
                                aria-valuemin="0" 
                                aria-valuemax="{{$communityRecords}}">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="d-flex mb-4 pb-2">
                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityAC">
                                <i class='bx bx-message-alt-detail'></i>
                            </a>
                        </span>
                        @include('employee.community.service.ac_survey')
                    </div>
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex justify-content-between mb-1">
                            <span>AC Communitites</span>
                            <span class="text-muted">
                                @if($communityAC)
                                    {{$communityAC}}
                                @endif
                            </span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-warning" style="width: {{$communityAC}}%" 
                                role="progressbar" aria-valuenow="{{$communityAC}}" aria-valuemin="0" 
                                aria-valuemax="{{$communityRecords}}">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="d-flex mb-4 pb-2">
                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-initial rounded-circle bg-label-success">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communitySurveyed">
                                <i class='bx bx-bulb'></i>
                            </a>
                        </span>
                        @include('employee.community.service.surveyed')
                        </span>
                    </div>
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Surveyed Communities</span>
                            <span class="text-muted">
                                {{$communitySurvyed}}
                            </span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-success" style="width: {{$communitySurvyed}}%" 
                                role="progressbar" aria-valuenow="{{$communitySurvyed}}" aria-valuemin="0" 
                                aria-valuemax="{{$communityRecords}}">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Water Service</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">
                                @if ($communityWater)
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityWater">
                                        {{$communityWater}}
                                    </a>
                                   
                                @endif
                            </h4> <small>Communities</small>
                            @include('employee.community.service.water')
                        </div>
                        
                            @if ($communityWater)
                            <?php
                                $min = $communityRecords - $communityWater;
                            ?>

                                @if($min < $communityRecords/2)
                                    <small class="text-success">{{$min}}
                                @else 
                                    <small class="text-danger">{{$min}}
                                @endif
                                
                            @endif
                        </small>
                        <small>Remaining</small>
                    </div>
                    <span class="badge bg-label-primary rounded p-2">
                        <i class="bx bx-water bx-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Internet Service</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">
                                @if ($communityInternet)
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityInternet">
                                        {{$communityInternet}}
                                    </a>
                                  
                                @endif
                            </h4>  
                            <small>Communities</small>
                            @include('employee.community.service.internet')
                            
                        </div>
                        @if ($communityInternet)
                        <?php
                            $min = $communityRecords - $communityInternet;
                        ?>
                            
                            @if($min < $communityRecords/2)
                                <small class="text-success">{{$min}}
                            @else 
                                <small class="text-danger">{{$min}}
                            @endif
                        @endif
                        </small>
                        <small>Remaining</small>
                    </div>
                    <span class="badge bg-label-success rounded p-2">
                        <i class="bx bx-wifi bx-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#">
                    Create New Community
                </button>

            </div>
            <table id="communityTable" class="table table-striped data-table-communities my-2">
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
                <input type="hidden" name="txtCommunityId" id="txtCommunityId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $(function () {
            var table = $('.data-table-communities').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('community.index') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    {data: 'english_name', name: 'english_name'},
                    {data: 'arabic_name', name: 'arabic_name'},
                    {data: 'number_of_people', name: 'number_of_people'},
                    {data: 'name', name: 'name'},
                    {data: 'subname', subname: 'name'},
                    {data: 'action'}
                ]
            });
            
        });
    </script>


<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <p class="card-text">
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunity">
                        Create New Community	
                    </button>

                    @include('employee.community.create')

                    <a href="{{url('community', 'create')}}" 
						class="btn btn-primary">Calculate Number of Households
                    </a>
                </div>
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
                                <a data-bs-target="#communityMap{{$community->id}}"
                                   type="button" data-bs-toggle="modal" title="View Map">
                                    <i class="fas fa-map" style="color:orange;"></i>
                                </a>
                                @include('employee.community.map')
                                <a data-bs-target="#communityImage{{$community->id}}"
                                   type="button" data-bs-toggle="modal" title="Add Images">
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
