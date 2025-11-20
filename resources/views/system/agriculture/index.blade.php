@extends('layouts/layoutMaster')

@section('title', 'agriculture-system')

@include('layouts.all')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseAgricultureSystemVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseAgricultureSystemVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseAgricultureSystemVisualData">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>System By Type</h5>
                    </div>
                    <div class="card-body">
                        <div id="AgricultureSystemTypeChart"></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Agriculture Systems
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
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                        Auth::guard(name: 'user')->user()->user_type_id == 2 ||
                        Auth::guard('user')->user()->user_type_id == 14)
                        <a type="button" class="btn btn-success me-2" 
                            href="{{url('agriculture-system', 'create')}}">
                            <i class="fa-solid fa-plus"></i> Create New Agriculture System	
                        </a>
                    @endif
                </div>

            </div>
            <table id="systemAgricultureTable" class="table table-striped data-table-agriculture-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">System Name</th>
                        <th class="text-center">Azolla Type</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($systems as $system)
                    <tr>
                        <td>{{ $system->name }}</td>

                        <td class="text-center">{{ $system->azollaType ? $system->azollaType->name : '-' }}</td>
                        <td>{{ Str::limit($system->description, 50)  }}</td>
                        <td class="text-center">{{ $system->installation_year }}</td>
                        <td class="text-center">
                            <a type="button" class="viewAgricultureSystem" data-id="{{ $system->id }}"><i class="fa-solid fa-eye text-info"></i></a>
                            @if(Auth::guard('user')->user() && in_array(Auth::guard('user')->user()->user_type_id, [1, 2, 14]))
                                <a type="button" class="updateAgricultureSystem" data-id="{{ $system->id }}"><i class="fa-solid fa-pen-to-square text-success"></i></a>
                                <a type="button" class="deleteAgricultureSystem" data-id="{{ $system->id }}"><i class="fa-solid fa-trash text-danger"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        var table = $('#systemAgricultureTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, 'asc']], // Order by System Name ascending
            columnDefs: [
                { 
                    targets: 5, // Actions column (6th column, 0-indexed = 5)
                    orderable: false, 
                    searchable: false,
                    width: '12%'
                },
                {
                    targets: 0, // System Name column
                    width: '20%'
                },
                {
                    targets: 1, // Fake Meter Number column
                    width: '18%'
                },
                {
                    targets: 2, // Azolla Type column
                    width: '15%'
                },
                {
                    targets: 3, // Description column
                    width: '25%'
                },
                {
                    targets: 4, // Year column
                    width: '10%'
                }
            ],
            language: {
                processing: "Loading agriculture systems...",
                emptyTable: "No agriculture systems found",
                zeroRecords: "No matching agriculture systems found"
            },
            drawCallback: function() {
                // Initialize tooltips after table draw
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        // Initialize tooltips on page load
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Store table reference globally for use in delete function
        window.agricultureSystemsTable = table;
    });

    // View Agriculture System functionality
    $('#systemAgricultureTable').on('click', '.viewAgricultureSystem', function() {
        var systemAgricultureId = $(this).data('id');
        var viewButton = $(this);
        var originalHtml = viewButton.html();
        

        
        // Set loading message in modal
        $('#viewSystemAgricultureModal').modal('show');
        
        $.get('agriculture-system/' + systemAgricultureId, function(data) {
            $('#systemAgricultureHolder').html(data);
            // Restore button state
            viewButton.html(originalHtml);
            viewButton.prop('disabled', false);
        }).fail(function(xhr) {
            // Restore button state
            viewButton.html(originalHtml);
            viewButton.prop('disabled', false);
            
            let errorMessage = 'Error loading system details';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Agriculture system not found';
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to view this system';
            }
            
            $('#systemAgricultureHolder').html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i><br>' + errorMessage + '</div>');
        });
    });

    // Update Agriculture System functionality
    $('#systemAgricultureTable').on('click', '.updateAgricultureSystem', function() {
        var systemAgricultureId = $(this).data('id');
        var updateButton = $(this);
        var originalHtml = updateButton.html();
        
        // Show loading state
        updateButton.prop('disabled', true);
        
        // Check if system exists before redirecting
        $.get('agriculture-system/' + systemAgricultureId + '/edit')
            .done(function() {
                window.location.href = 'agriculture-system/' + systemAgricultureId + '/edit';
            })
            .fail(function(xhr) {
                // Restore button state
                updateButton.html(originalHtml);
                updateButton.prop('disabled', false);
                
                let errorMessage = 'Error accessing edit page';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Agriculture system not found';
                } else if (xhr.status === 403) {
                    errorMessage = 'You do not have permission to edit this system';
                }
                alert(errorMessage);
            });
    });

    // Delete Agriculture System functionality
    $('#systemAgricultureTable').on('click', '.deleteAgricultureSystem', function() {
        var systemAgricultureId = $(this).data('id');
        deleteSystem(systemAgricultureId);
    });

    // Refresh table functionality
    $('#refreshTable').on('click', function() {
        var refreshButton = $(this);
        var originalHtml = refreshButton.html();
        
        refreshButton.html('<i class="fa fa-spinner fa-spin"></i> Refreshing...');
        refreshButton.prop('disabled', true);
        
        // Simulate refresh by reloading the page or you can implement AJAX reload
        setTimeout(function() {
            location.reload();
        }, 500);
    });

    // Export table functionality
    $('#exportTable').on('click', function() {
        var exportButton = $(this);
        var originalHtml = exportButton.html();
        
        exportButton.html('<i class="fa fa-spinner fa-spin"></i> Exporting...');
        exportButton.prop('disabled', true);
        
        // Simple CSV export
        try {
            var table = window.agricultureSystemsTable;
            var data = table.data().toArray();
            var csv = 'System Name,Fake Meter Number,Azolla Type,Description,Year\n';
            
            data.forEach(function(row) {
                // Extract text content and clean it
                var name = $(row[0]).text() || row[0];
                var fakeMeterNumber = $(row[1]).text() || row[1];
                var azollaType = $(row[2]).text() || row[2];
                var description = $(row[3]).text() || row[3];
                var year = $(row[4]).text() || row[4];
                
                csv += '"' + name.replace(/"/g, '""') + '","' + 
                       fakeMeterNumber.replace(/"/g, '""') + '","' + 
                       azollaType.replace(/"/g, '""') + '","' + 
                       description.replace(/"/g, '""') + '","' + 
                       year.replace(/"/g, '""') + '"\n';
            });
            
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            var url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'agriculture_systems_' + new Date().toISOString().split('T')[0] + '.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
        } catch (error) {
            alert('Error exporting data: ' + error.message);
        }
        
        // Restore button state
        setTimeout(function() {
            exportButton.html(originalHtml);
            exportButton.prop('disabled', false);
        }, 1000);
    });

    // Delete record - using SweetAlert2 like water system
    function deleteSystem(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this system?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                // Show loading state
                var deleteButton = $('.deleteAgricultureSystem[data-id="' + id + '"]');
                var originalHtml = deleteButton.html();
                deleteButton.html('<i class="fa fa-spinner fa-spin"></i>');
                deleteButton.prop('disabled', true);

                $.ajax({
                    url: 'agriculture-system/' + id,
                    type: 'DELETE',
                    data: { 
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if(response.success == 1 || response.message) {
                            deleteButton.html(originalHtml);
                            deleteButton.prop('disabled', false);
                            
                            Swal.fire({
                                icon: 'success',
                                title: response.message || 'Agriculture system deleted successfully!',
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                location.reload();
                            });
                        } else {
                            // Restore button state
                            deleteButton.html(originalHtml);
                            deleteButton.prop('disabled', false);
                            Swal.fire('Error', 'Failed to delete agriculture system', 'error');
                        }
                    },
                    error: function(xhr) {
                        // Restore button state
                        deleteButton.html(originalHtml);
                        deleteButton.prop('disabled', false);
                        
                        let errorMessage = 'Error deleting agriculture system';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Agriculture system not found';
                        } else if (xhr.status === 403) {
                            errorMessage = 'You do not have permission to delete this system';
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
            }
        });
    }
</script>



<!-- View System Modal -->
<div class="modal fade" id="viewSystemAgricultureModal" tabindex="-1" role="dialog" aria-labelledby="viewSystemAgricultureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSystemAgricultureModalLabel">Agriculture System Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="systemAgricultureHolder">
                    <!-- System details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection