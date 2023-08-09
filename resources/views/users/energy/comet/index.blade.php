@extends('layouts/layoutMaster')

@section('title', 'comet meters')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Comet Meters
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.energy.comet.details')

<div class="container mb-4">
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMeterPublic">
                        Create New Comet Meter	
                    </button>
                    @include('users.energy.comet.create')
                </div>
            @endif
            <table id="energyCometMeterTable" 
                class="table table-striped data-table-energy-comet-meters my-2">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Energy System</th>
                        <th class="text-center">Energy System Type</th>
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

        var table = $('.data-table-energy-comet-meters').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('comet-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });

        // Delete record
        $('#energyCometMeterTable').on('click', '.deleteEnergyComet',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this comet meter?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCometMeter') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#energyCometMeterTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if(result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        // View record update page
        $('#energyCometMeterTable').on('click', '.updateEnergyComet',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'comet-meter/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energyCometMeterTable').on('click', '.viewCometMeterUser',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-public/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#energyCometModalTitle').html(" ");
                    $('#englishNameComet').html(" ");
                    $('#communityComet').html(" ");
                    $('#meterActiveComet').html(" ");
                    $('#meterCaseComet').html(" ");
                    $('#systemNameComet').html(" ");
                    $('#systemTypeComet').html(" ");
                    $('#systemLimitComet').html(" ");
                    $('#systemDateComet').html(" ");
                    $('#systemNotesComet').html(" ");

                    $('#energyCometModalTitle').html(response['public'].english_name);
                    $('#englishNameComet').html(response['public'].english_name);
                    $('#communityComet').html(response['community'].english_name);
                    $('#meterActiveComet').html(response['energyPublic'].meter_active);
                    $('#meterCaseComet').html(response['meter'].meter_case_name_english);
                    $('#systemNameComet').html(response['system'].name);
                    $('#systemTypeComet').html(response['type'].name);
                    $('#systemLimitComet').html(response['energyPublic'].daily_limit);
                    $('#systemDateComet').html(response['energyPublic'].installation_date);
                    $('#systemNotesComet').html(response['energyPublic'].notes);
                    if(response['vendor']) $('#vendorDatePublic').html(response['vendor'].name);
                    $('#installationTypeComet').html(" ");
                    if(response['installationType']) $('#installationTypePublic').html(response['installationType'].type);

                    $('#donorsDetails').html(" ");
                    if(response['energyMeterDonors'] != []) {
                        for (var i = 0; i < response['energyMeterDonors'].length; i++) {
                            if(response['energyMeterDonors'][i].donor_name == "0")  {
                                response['energyMeterDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#donorsDetails").append(
                            '<ul><li>'+ response['energyMeterDonors'][i].donor_name +'</li></ul>');  
                        }
                    }
                }
            });
        });
    });
</script>
@endsection