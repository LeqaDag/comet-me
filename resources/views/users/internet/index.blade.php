@extends('layouts/layoutMaster')

@section('title', 'all internet users')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Internet Contract Holders
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
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('internet-user.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community"
                                class="form-control">
                                <option disabled selected>Search Community</option>
                                @foreach($communities as $community)
                                <option value="{{$community->english_name}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="donor"
                                class="form-control">
                                <option disabled selected>Search Donor</option>
                                @foreach($donors as $donor)
                                <option value="{{$donor->id}}">
                                    {{$donor->donor_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <input type="date" name="start_date" 
                            class="form-control" title="Data from"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button class="btn btn-info" type="submit">
                            <i class='fa-solid fa-file-excel'></i>
                            Export Excel
                        </button>
                    </div>
                </div> 
            </form>

            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 6 ||
                Auth::guard('user')->user()->user_type_id == 10 )
                <div style="margin-top:30px">
                    <button type="button" class="btn btn-success" 
                        id="getInternetHolders">
                        Get Latest Internet Holders
                    </button>
                </div>
            @endif
        </div>
       
        <div class="card-body">
            <table id="internetAllUsersTable" class="table table-striped data-table-internet-users my-2">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Public Structure</th>
                        <th>Community</th>
                        <th>Date</th>
                        <th># of Contracts</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10 )
                            <th>Options</th>
                        @else
                            <th></th>
                        @endif
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
        var table = $('.data-table-internet-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'start_date', name: 'start_date'},
                {data: 'number_of_contract', name: 'number_of_contract'},
                {data: 'action'}
            ]
        });
    });

    // View record details
    $('#getInternetHolders').on('click', function() {

        // AJAX request
        $.ajax({
            url: 'api/internet-holder',
            type: 'get',
            dataType: 'json',
            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Internet Contract Holders Gotten Successfully!',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!'
                }).then((result) => {

                    $('#internetAllUsersTable').DataTable().draw();
                });
            }
        });
    });

</script>
@endsection