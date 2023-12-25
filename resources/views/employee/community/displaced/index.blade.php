@extends('layouts/layoutMaster')

@section('title', 'displaced communities')

@include('layouts.all')

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    @if ($communityRecords)
        {{$communityRecords}}
    @endif
  <span class="text-muted fw-light">Displaced </span> communities
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <table id="communityDisplacedTable" 
                class="table table-striped data-table-displaced-communities my-2">
                <thead>
                    <tr>
                        <th class="text-center">Displaced Community</th>
                        <th class="text-center">Displaced Region</th>
                        <th class="text-center"># of Displaced Families</th>
                        <th class="text-center">New Community</th>
                        <th class="text-center">New Region</th>
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
    
        var table = $('.data-table-displaced-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('displaced-community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'name', name: 'name'},
                {data: 'number_of_household', name: 'number_of_household'},
                {data: 'new_community', name: 'new_community'},
                {data: 'new_region', name: 'new_region'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#communityDisplacedTable').on('click', '.displacedCommunityButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
        });
    
    });
</script>
@endsection