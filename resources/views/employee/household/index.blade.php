@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'households')


@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

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
  <span class="text-muted fw-light"> </span> 
</h4>

<ul class="nav nav-pills nav-fill">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#allHouseholdsTab">All Households</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#initialHouseholdsTab">Initial Households</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#acHouseholdsTab">AC Survey Households</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#surveyedHouseholdsTab">Surveyed Households</a>
    </li>
</ul>

<div class="tab-content">
    <div id="allHouseholdsTab" class="tab-pane active">
        <div class="container">
            <div class="card my-2">
                <div class="card-body">
                    <div>
                        <p class="card-text">
                            <div>
                                <a type="button" class="btn btn-success" 
                                    href="{{url('household', 'create')}}" >
                                    Create New Household	
                                </a>
                            </div>
                        </p>

                    </div>
                    <table id="householdsTable" 
                        class="table table-striped data-table-households my-2">
                        <thead>
                            <tr>
                                <th class="text-center">English Name</th>
                                <th class="text-center">Arabic Name</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <input type="hidden" name="txtHouseholdId" id="txtHouseholdId" value="0">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    
    </div>

    <div id="initialHouseholdsTab" class="tab-pane fade">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <p class="card-text">
                        <div>
                        
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="acHouseholdsTab">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <p class="card-text">
                        <div>
                        
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="surveyedHouseholdsTab">
        <div class="card">
            <div class="card-content collapse show">
                <div class="card-body">
                    <p class="card-text">
                        <div>
                        
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var table = $('.data-table-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                { data: 'action' }
            ]
        });
        $('#status').change(function() {
            table.draw();
        });
    
    });
</script>
@endsection