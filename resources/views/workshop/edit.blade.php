@extends('layouts/layoutMaster')

@section('title', 'edit workshop data')

@include('layouts.all')

<style>
    label, input {

        display: block;
    }
    label {

        margin-top: 20px;
    }

</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> 
        @if($workshopCommunity->compound_id)
            {{$workshopCommunity->Community->english_name}}
        @else
            {{$workshopCommunity->Community->english_name}}
        @endif
        - {{$workshopCommunity->WorkshopType->english_name}}
    <span class="text-muted fw-light"> Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-workshop.update', $workshopCommunity->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    @if($workshopCommunity->compound_id)
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="compound_id" disabled>
                                @if($workshopCommunity->compound_id)
                                    <option value="{{$workshopCommunity->compound_id}}">
                                        {{$workshopCommunity->Compound->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    @endif
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($workshopCommunity->community_id)
                                    <option value="{{$workshopCommunity->community_id}}">
                                        {{$workshopCommunity->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Workshop Type</label>
                            <select class=" form-control" name="workshop_type_id" disabled>
                                @if($workshopCommunity->workshop_type_id)
                                    <option value="{{$workshopCommunity->workshop_type_id}}">
                                        {{$workshopCommunity->WorkshopType->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Workshop Date</label>
                            <input type="date" name="date" value="{{$workshopCommunity->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of Hours</label>
                            <input type="number" name="number_of_hours" value="{{$workshopCommunity->number_of_hours}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of Male</label>
                            <input type="number" name="number_of_male" class="form-control"
                            value="{{$workshopCommunity->number_of_male}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of Female</label>
                            <input type="number" name="number_of_female" class="form-control"
                                value="{{$workshopCommunity->number_of_female}}">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of Youth</label>
                            <input type="number" name="number_of_youth" class="form-control"
                                value="{{$workshopCommunity->number_of_youth}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Lead By</label>
                            <select class="selectpicker form-control" name="lead_by"
                                data-live-search="true">
                                @if($workshopCommunity->lead_by)
                                    <option value="{{$workshopCommunity->lead_by}}">
                                        {{$workshopCommunity->User->name}}
                                    </option>
                                @endif   
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="7">
                                {{$workshopCommunity->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Feedback</label>
                            <textarea name="stories" class="form-control" 
                                style="resize:none" cols="20" rows="7">
                                {{$workshopCommunity->stories}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h5>Workshop Co-Trainers</h5>
                </div>
                @if(count($workshopCommunityCoTrainers) > 0) 

                    <table id="workshopCommunityCoTrainersTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($workshopCommunityCoTrainers as $workshopCommunityCoTrainer)
                            <tr id="workshopCommunityCoTrainerRow">
                                <td class="text-center">
                                    {{$workshopCommunityCoTrainer->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWorkshopCommunityCoTrainer" id="deleteWorkshopCommunityCoTrainer" 
                                        data-id="{{$workshopCommunityCoTrainer->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add More Co-Trainers</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="more_co_trainers[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($coTrainers as $coTrainer)
                                        <option value="{{$coTrainer->id}}">
                                            {{$coTrainer->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Co-Trainers</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_co_trainers[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($coTrainers as $coTrainer)
                                        <option value="{{$coTrainer->id}}">
                                            {{$coTrainer->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif
                <br>

                <hr>
                <div class="row">
                    <h5>Workshop Photos</h5>
                </div>
                @if(count($workshopCommunityPhotos) > 0)

                    <table id="workshopCommunityPhotosTable" 
                        class="table table-striped data-table-fbs-equipments my-2">
                        
                        <tbody>
                            @foreach($workshopCommunityPhotos as $workshopCommunityPhoto)
                            <tr id="workshopCommunityPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/workshops/'.$workshopCommunityPhoto->name)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWorkshopPhoto" id="deleteWorkshopPhoto" 
                                        data-id="{{$workshopCommunityPhoto->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload More photos</label>
                            <input type="file" name="more_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                @else 
                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload new photos</label>
                            <input type="file" name="new_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                @endif

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    // delete Workshop Photo
    $('#workshopCommunityPhotosTable').on('click', '.deleteWorkshopPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWorkshopPhoto') }}",
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
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // delete Co-Trainer
    $('#workshopCommunityCoTrainersTable').on('click', '.deleteWorkshopCommunityCoTrainer',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this co-trainer?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWorkshopCommunityCoTrainer') }}",
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
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // delete damaged equipment
    $('#fbsIncidentEquipmentsTable').on('click', '.deleteIncidentEquipment',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this equipment?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "/delete-fbs-equipment/" + id,
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
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

</script>

@endsection