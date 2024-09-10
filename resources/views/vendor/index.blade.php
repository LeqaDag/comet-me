@extends('layouts/layoutMaster')

@section('title', 'vendor')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseVendorExport" aria-expanded="false" 
        aria-controls="collapseVendorExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseVendorExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Vending Points Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearVendorFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('vendor.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="vendor_region" class="selectpicker form-control"
                                            data-live-search="true">
                                            <option disabled selected>Search Region</option>
                                            @foreach($vendorRegions as $vendorRegion)
                                                <option value="{{$vendorRegion->id}}">
                                                    {{$vendorRegion->english_name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-file-excel'></i>
                                            Export Excel
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div> 
    </div> 
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Vending Points
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
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter by Region</label>
                        <select name="vendor_region" class="selectpicker form-control"
                            data-live-search="true" id="filterByRegion">
                            <option disabled selected>Search Region</option>
                            @foreach($vendorRegions as $vendorRegion)
                                <option value="{{$vendorRegion->id}}">
                                    {{$vendorRegion->english_name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter by Community</label>
                        <select name="vendor_community" class="selectpicker form-control"
                            data-live-search="true" id="filterByCommunity">
                            <option disabled selected>Search Community</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter by Town</label>
                        <select name="vendor_town" class="selectpicker form-control"
                            data-live-search="true" id="filterBytown">
                            <option disabled selected>Search Town</option>
                            @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card-header">
                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 2 ||
                    Auth::guard('user')->user()->user_type_id == 4 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createVendingPoint">
                            Create New Vending Point	
                        </button>
                        @include('vendor.create')
                    </div>
                @endif
            </div>

            <table id="vendingPointTable" class="table table-striped data-table-vending-point my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th>
                        <th>Region</th>
                        <th>Username</th>
                        <th>Phone Number</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> 

@include('vendor.show')

<script type="text/javascript">

    var table;
    function DataTableContent() {

        table = $('.data-table-vending-point').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('vendor.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.region_filter = $('#filterByRegion').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.town_filter = $('#filterBytown').val();
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'region', name: 'region'},
                {data: 'name', name: 'name'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterBytown').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-vending-point')) {
                $('.data-table-vending-point').DataTable().destroy();
            }
            DataTableContent();
        });
    });

    // Delete record
    $('#vendingPointTable').on('click', '.deleteVendor',function() {
        var id = $(this).data('id');

        Swal.fire({ 
            icon: 'warning',
            title: 'Are you sure you want to delete this vendor?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteVendor') }}",
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
                                $('#vendingPointTable').DataTable().draw();
                            });
                        } else {

                            alert("Invalid ID.");
                        }
                    }
                });
            } else if (result.isDenied) {

                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // Clear Filters for Export
    $('#clearVendorFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View record details
    $('#vendingPointTable').on('click', '.detailsVendorButton',function() {

        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'vendor/' + id,
            type: 'get',
            dataType: 'json', 
            success: function(response) { 

                $('#vendorModalTitle').html(" ");
                $('#englishNameVendingPoint').html(" ");
                $('#arabicNameVendingPoint').html(" ");

                $('#vendorModalTitle').html(response['vendor'].english_name);
                $('#englishNameVendingPoint').html(response['vendor'].english_name);
                $('#arabicNameVendingPoint').html(response['vendor'].arabic_name);
                
                $('#phoneNumberVendingPoint').html(" ");
                $('#phoneNumberVendingPoint').html(response['vendor'].phone_number);
                $('#additionalphoneNumberVendingPoint').html(" ");
                $('#additionalphoneNumberVendingPoint').html(response['vendor'].additional_phone_number);
                $('#usernameVendingPoint').html(" ");
                if(response['vendorUserName']) $('#usernameVendingPoint').html(response['vendorUserName'].name);

                $('#regionVendingPoint').html(" ");
                if(response['vendorRegion']) $('#regionVendingPoint').html(response['vendorRegion'].english_name);

                $('#locationVendingPoint').html(" ");
                if(response['community']) $('#locationVendingPoint').html(response['community'].english_name); 
                else if(response['town']) $('#locationVendingPoint').html(response['town'].english_name);

                $('#notesVendingPoint').html(" ");
                $('#notesVendingPoint').html(response['vendor'].notes);

                $('#communitiesVendingPoint').html(" ");
                if(response['vendorCommunities'] != []) {
                    for (var i = 0; i < response['vendorCommunities'].length; i++) {
                        $("#communitiesVendingPoint").append(
                        '<ul><li>'+ response['vendorCommunities'][i].english_name +'</li></ul>');  
                    }
                }
            }
        });
    }); 

    // View update
    $('#vendingPointTable').on('click', '.updateVendor',function() {
        var id = $(this).data('id');

        var url = window.location.href; 
        
        url = url +'/'+ id +'/edit';
        window.open(url, "_self"); 
    });

</script>
@endsection