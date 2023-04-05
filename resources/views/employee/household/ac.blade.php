@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'AC Survey households')

@include('layouts.all')

@section('content')


<div class="container mb-4 my-2">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-header">
                    <h5>AC Survey Households by Community</h5>
                </div>
                <div class="panel-body" >
                    <div id="community_ac_households_chart" style="height:300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span>AC Survey Households
</h4>


@include('employee.household.sub_household')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <p class="card-text">
                    <div>
                        <a type="button" class="btn btn-success" 
                            href="{{url('ac-household', 'create')}}" >
                             Create New Elc.
                        </a>
                    </div>
                </p>
            </div>
            <table id="acHouseholdsTable" 
                class="table table-striped data-table-ac-households my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Main Household?</th>
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

        var analytics = <?php echo $communityAcHouseholdsData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'AC Survey Households by Community' 
            };

            var chart = new google.charts.Bar(document.getElementById('community_ac_households_chart'));
            chart.draw(data, options);
        }

        var table = $('.data-table-ac-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ac-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'region_name', name: 'region_name'},
                {data: 'action'}
            ]
        });

        // Change status 
        $('#acHouseholdsTable').on('change', '.sharedHousehold',function() {
            var id = $(this).data('id');
            var isShared = $(this).val();

            if(isShared == "No" ){
                $.ajax({
                    url: "{{ route('acSubHousehold') }}",
                    type: 'get',
                    data: {
                        id: id,
                        isShared: isShared
                    },
                    success: function(response) {
                        $('#subHouseholdModal').modal('toggle');
                        $(".mainHousehold").html("");
                        response.forEach(el => {
                            $(".mainHousehold").append(`<option value='${el.id}'> ${el.english_name}</option>`)
                        }); 

                        $('#btn_save').click(function (e) {
                            e.preventDefault();
                            var user_id = $('#mainHousehold').val();
                        
                            $.ajax({
                                url: "{{ route('acSubHouseholdSave') }}",
                                type: 'get',
                                data: {
                                    id: id,
                                    user_id: user_id,
                                },
                                success: function (data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: "Household Meter Updated Successfully!",
                                        showDenyButton: false,
                                        showCancelButton: false,
                                        confirmButtonText: 'Okay!'
                                    }).then((result) => {
                                        $('#acHouseholdsTable').DataTable().draw();
                                    });

                                    $('#subHouseholdModal').modal('hide');
                                    table.draw();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn_save').html('Save Changes');
                                }
                            });
                        });
                    }
                });
            } else if(isShared == "Yes") {
                $.ajax({
                    url: "{{ route('acMainHousehold') }}",
                    type: 'get',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: "Energy User Updated Successfuly!",
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {
                            $('#acHouseholdsTable').DataTable().draw();
                        });
                    }
                });
            }
           

            // Swal.fire({
            //     icon: 'warning',
            //     title: 'Are you sure you want to change the status for this household to Served?',
            //     showDenyButton: false,
            //     showCancelButton: true,
            //     confirmButtonText: 'Confirm'
            // }).then((result) => {
               
            // });
        });
    
    });
</script>
@endsection