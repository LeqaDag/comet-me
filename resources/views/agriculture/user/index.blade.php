@extends('layouts/layoutMaster')

@section('title', 'Agriculture Users')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/js/extended-ui-sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/tables-datatables-basic.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Agriculture Users
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@if(session()->has('error'))
    <div class="row">
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    </div>
@endif

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                        Auth::guard('user')->user()->user_type_id == 2 ||
                        Auth::guard('user')->user()->user_type_id == 14)
                        <a type="button" class="btn btn-success me-2" 
                            href="{{url('argiculture-user', 'create')}}">
                            <i class="fa-solid fa-plus fs-5"></i> Create New Agriculture User
                        </a>
                    @endif
                </div>
                <!-- <div>
                    <button type="button" class="btn btn-info" id="exportBtn">
                        <i class="fa-solid fa-download fs-5"></i> Export
                    </button>
                </div> -->
            </div>


            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="agricultureUserTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="requested-tab" data-bs-toggle="tab" data-bs-target="#requested-holders" type="button" role="tab" aria-controls="requested-holders" aria-selected="true">
                        <i class="fas fa-clock me-2"></i> All Requested Holders
                        <span class="badge  ms-2" style="background-color: #d6f7fa; color: #00cfdd;">{{ $requestedHolders->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed-holders" type="button" role="tab" aria-controls="confirmed-holders" aria-selected="false">
                        <i class="fas fa-check-circle me-2"></i> All Confirmed
                        <span class="badge ms-2" style="background-color: #bcbdbe; color: #28a745;">{{ $confirmedHolders->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress-holders" type="button" role="tab" aria-controls="progress-holders" aria-selected="false">
                        <i class="fas fa-spinner me-2"></i> All In Progress
                        <span class="badge ms-2" style="background-color: #e7ebef; color: #69809a;">{{ $progressHolders->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-holders" type="button" role="tab" aria-controls="completed-holders" aria-selected="false">
                        <i class="fas fa-check-double me-2"></i> All Completed
                        <span class="badge ms-2" style="background-color: #dff9ec; color: #3fdb8d;">{{ $completedHolders->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3" id="agricultureUserTabContent">
                <!-- All Requested Holders Tab -->
                <div class="tab-pane fade show active" id="requested-holders" role="tabpanel" aria-labelledby="requested-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            @if(Auth::guard('user')->user()->user_type_id == 1 || 
                                Auth::guard('user')->user()->user_type_id == 2 ||
                                Auth::guard('user')->user()->user_type_id == 14)
                               <form action="{{route('data-collection.import-requested-agriculture')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="excel_file">Choose Excel File</label>
                                        <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                                        @error('excel_file')
                                        <div class="text-success mt-2">{{ $message }}</div>
                                        @enderror
                                    </div> <br>
                                    <button type="submit" class="btn btn-warning btn-block">
                                        
                                        <i class='fa-solid fa-upload'></i>
                                        Import Requested Holders
                                    </button>
                                </form>

                            @endif
                        </div>

                    </div>
                    <table id="requestedHoldersTable" class="table table-striped my-2">
                        <thead>
                            <tr>
                                <th class="text-center">Household Name</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Agriculture System</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requestedHolders as $holder)
                            <tr>
                                <td>{{ $holder->household->english_name ?? 'N/A' }}</td>
                                <td>{{ $holder->community->english_name ?? 'N/A' }}</td>
                                <td>
                                    @if($holder->agricultureSystems->count() > 0)
                                        <div class="fw-medium">
                                            {{ $holder->agricultureSystems->pluck('name')->filter()->join(', ') ?: 'Unknown Systems' }}
                                        </div>
                                        <small class="text-muted">{{ $holder->size_of_herds }} sheep • {{ $holder->azolla_unit }} units</small>
                                    @else
                                        <span class="text-muted">No systems assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($holder->requested_date)
                                        {{ is_string($holder->requested_date) ? $holder->requested_date : $holder->requested_date->format('Y-m-d') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('argiculture-user.show', $holder->id) }}" class="text-decoration-none me-2" title="View Details">
                                        <i class="fas fa-eye text-primary fs-5"></i>
                                    </a>
                                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                                        Auth::guard('user')->user()->user_type_id == 2 ||
                                        Auth::guard('user')->user()->user_type_id == 14)
                                    <a href="{{ route('argiculture-user.edit', $holder->id) }}" class="text-decoration-none me-2" title="Edit">
                                        <i class="fa-solid fa-edit text-warning fs-5"></i>
                                    </a>
                                    <a href="#" class="text-decoration-none me-2" title="Approve" onclick="approveHolder({{ $holder->id }})">
                                        <i class="fas fa-check text-success fs-5"></i>
                                    </a>
                                    <a href="#" class="text-decoration-none" title="Reject" onclick="rejectHolder({{ $holder->id }})">
                                        <i class="fas fa-times text-danger fs-5"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No requested holders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- All Confirmed Tab -->
                <div class="tab-pane fade" id="confirmed-holders" role="tabpanel" aria-labelledby="confirmed-tab">
                    <table id="confirmedHoldersTable" class="table table-striped my-2">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Agriculture System</th>
                                <th class="text-center">Confirmation Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($confirmedHolders as $holder)
                            <tr>
                                <td>{{ $holder->household->english_name ?? 'N/A' }}</td>
                                <td>{{ $holder->community->english_name ?? 'N/A' }}</td>
                                <td>
                                    @if($holder->agricultureSystems->count() > 0)
                                        <div class="fw-medium text-success">
                                            {{ $holder->agricultureSystems->pluck('name')->filter()->join(', ') ?: 'Unknown Systems' }}
                                        </div>
                                        <small class="text-muted">{{ $holder->size_of_herds }} sheep • {{ $holder->azolla_unit }} units</small>
                                    @else
                                        <span class="text-muted">No systems assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($holder->requested_date)
                                        {{ is_string($holder->requested_date) ? $holder->requested_date : $holder->requested_date->format('Y-m-d') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('argiculture-user.show', $holder->id) }}" class="text-decoration-none me-2" title="View Details">
                                        <i class="fa-solid fa-eye text-primary fs-5"></i>
                                    </a>
                                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                                        Auth::guard('user')->user()->user_type_id == 2 ||
                                        Auth::guard('user')->user()->user_type_id == 14)
                                    <a href="{{ route('argiculture-user.edit', $holder->id) }}" class="text-decoration-none me-2" title="Edit">
                                        <i class="fa-solid fa-edit text-warning fs-5"></i>
                                    </a>
                                    <a href="#" class="text-decoration-none" title="Move to Progress" onclick="moveToProgress({{ $holder->id }})">
                                        <i class="fa-solid fa-arrow-right text-info fs-5"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                    No confirmed holders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- All In Progress Tab -->
                <div class="tab-pane fade" id="progress-holders" role="tabpanel" aria-labelledby="progress-tab">
                    <table id="progressHoldersTable" class="table table-striped my-2">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Agriculture System</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($progressHolders as $holder)
                            <tr>
                                <td>{{ $holder->household->english_name ?? 'N/A' }}</td>
                                <td>{{ $holder->community->english_name ?? 'N/A' }}</td>
                                <td>
                                    @if($holder->agricultureSystems->count() > 0)
                                        <div class="fw-medium text-warning">
                                            {{ $holder->agricultureSystems->pluck('name')->filter()->join(', ') ?: 'Unknown Systems' }}
                                        </div>
                                        <small class="text-muted">{{ $holder->size_of_herds }} sheep • {{ $holder->azolla_unit }} units</small>
                                    @else
                                        <span class="text-muted">No systems assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('argiculture-user.show', $holder->id) }}" class="text-decoration-none me-2" title="View Details">
                                        <i class="fa-solid fa-eye text-primary fs-5"></i>
                                    </a>
                                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                                        Auth::guard('user')->user()->user_type_id == 2 ||
                                        Auth::guard('user')->user()->user_type_id == 14)
                                    <a href="{{ route('argiculture-user.edit', $holder->id) }}" class="text-decoration-none me-2" title="Edit">
                                        <i class="fa-solid fa-edit text-warning fs-5"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-success" title="Mark Complete" onclick="markComplete({{ $holder->id }})">
                                        <i class="fa-solid fa-check-double"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-2x mb-2 d-block"></i>
                                    No holders in progress found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- All Completed Tab -->
                <div class="tab-pane fade" id="completed-holders" role="tabpanel" aria-labelledby="completed-tab">
                    <table id="completedHoldersTable" class="table table-striped my-2">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Agriculture System</th>
                                <th class="text-center">Completion Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedHolders as $holder)
                            <tr>
                                <td>{{ $holder->household->english_name ?? 'N/A' }}</td>
                                <td>{{ $holder->community->english_name ?? 'N/A' }}</td>
                                <td>
                                    @if($holder->agricultureSystems->count() > 0)
                                        <div class="fw-medium text-success">
                                            {{ $holder->agricultureSystems->pluck('name')->filter()->join(', ') ?: 'Unknown Systems' }}
                                        </div>
                                        <small class="text-muted">{{ $holder->size_of_herds }} sheep • {{ $holder->azolla_unit }} units</small>
                                    @else
                                        <span class="text-muted">No systems assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($holder->completed_date)
                                        {{ is_string($holder->completed_date) ? $holder->completed_date : $holder->completed_date->format('Y-m-d') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(Auth::guard('user')->user()->user_type_id == 1 || 
                                        Auth::guard('user')->user()->user_type_id == 2 ||
                                        Auth::guard('user')->user()->user_type_id == 14)
                                        <a href="{{ route('argiculture-user.edit', $holder->id) }}" class="text-decoration-none me-2" title="Edit">
                                            <i class="fa-solid fa-edit text-warning fs-5"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('argiculture-user.show', $holder->id) }}" class="text-decoration-none me-2" title="View Details">
                                        <i class="fa-solid fa-eye text-primary fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-check-double fa-2x mb-2 d-block"></i>
                                    No completed holders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Get CSRF token from meta tag
 */
function getCSRFToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    return metaToken ? metaToken.getAttribute('content') : '';
}

/**
 * Make AJAX request with CSRF token
 */
function makeAjaxRequest(url, method = 'POST', onSuccess = null, onError = null) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', getCSRFToken());
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200) {
                    if (onSuccess) onSuccess(response);
                } else {
                    if (onError) onError(response);
                }
            } catch (e) {
                if (onError) onError({ message: 'Invalid response format' });
            }
        }
    };

    xhr.send();
}

/**
 * Approve holder - change status to confirmed (status_id = 2)
 */
function approveHolder(holderId) {
    Swal.fire({
        title: 'Confirm Approval',
        text: 'Are you sure you want to approve this agriculture holder?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'No',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            makeAjaxRequest(
                '/argiculture-user/approve/' + holderId,
                'POST',
                function(response) {
                    if (response.success) {
                        Swal.fire('Approved', response.message || 'Agriculture holder approved.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'Approval failed.', 'error');
                    }
                },
                function(error) {
                    const message = error.message || 'An error occurred while approving the holder.';
                    Swal.fire('Error', message, 'error');
                }
            );
        }
    });
}

/**
 * Reject holder - change status to rejected
 */
function rejectHolder(holderId) {
    Swal.fire({
        title: 'Confirm Rejection',
        text: 'Are you sure you want to reject this agriculture holder?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'No',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            makeAjaxRequest(
                '/argiculture-user/reject/' + holderId,
                'POST',
                function(response) {
                    if (response.success) {
                        Swal.fire('Rejected', response.message || 'Agriculture holder rejected.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'Rejection failed.', 'error');
                    }
                },
                function(error) {
                    const message = error.message || 'An error occurred while rejecting the holder.';
                    Swal.fire('Error', message, 'error');
                }
            );
        }
    });
}

/**
 * Move holder to progress - change status to in progress
 */
function moveToProgress(holderId) {
    Swal.fire({
        title: 'Move to Progress',
        text: 'Are you sure you want to move this holder to progress?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'No',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            makeAjaxRequest(
                '/argiculture-user/move-to-progress/' + holderId,
                'POST',
                function(response) {
                    if (response.success) {
                        Swal.fire('Moved', response.message || 'Holder moved to progress.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'Move failed.', 'error');
                    }
                },
                function(error) {
                    const message = error.message || 'An error occurred while moving the holder to progress.';
                    Swal.fire('Error', message, 'error');
                }
            );
        }
    });
}

/**
 * Mark holder as complete - change status to completed
 */
function markComplete(holderId) {
    Swal.fire({
        title: 'Mark as Complete',
        text: 'Are you sure you want to mark this holder as complete?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'No',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            makeAjaxRequest(
                '/argiculture-user/mark-complete/' + holderId,
                'POST',
                function(response) {
                    if (response.success) {
                        Swal.fire('Completed', response.message || 'Holder marked as completed.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'Operation failed.', 'error');
                    }
                },
                function(error) {
                    const message = error.message || 'An error occurred while marking the holder as complete.';
                    Swal.fire('Error', message, 'error');
                }
            );
        }
    });
}
</script>

@endsection
