@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

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
  <span class="text-muted fw-light">All </span> Sub-Regions
</h4>

@include('regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createSubRegionModal">
                    Create New Sub-Region	
                </button>
                @include('regions.create')

            </div>
            <table id="subRegionTable" class="table table-striped data-table-regions my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtSubRegionId" id="txtSubRegionId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $(function () {
            var table = $('.data-table-regions').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('region.index') }}",
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

            // Update record
            $('#subRegionTable').on('click','.updateSubRegion',function() {
                var id = $(this).data('id');

                $('#txtSubRegionId').val(id);

                // AJAX request
                $.ajax({
                    url: 'getSubRegionData/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {

                        if(response.success == 1) {

                            $('#english_name').val(response.english_name);
                            $('#arabic_name').val(response.arabic_name);

                            // get region by id
                            $.ajax({
                                url: 'getRegionData/' + response.region_id,
                                type: 'get',
                                dataType: 'json',
                                success: function(response) {

                                    if(response.success == 1) {

                                        $('#selectedRegion').text(response.english_name);

                                        empTable.ajax.reload();
                                    } else {

                                        alert("Invalid ID.");
                                    }
                                }
                            });

                            empTable.ajax.reload();
                        } else {

                            alert("Invalid ID.");
                        }
                    }
                });

            });
        });
    </script>

@endsection