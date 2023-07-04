@extends('layouts/layoutMaster')

@section('title', 'settings')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    Update<span class="text-muted fw-light"> Setting Details</span> 
</h4>

<div class="container">
    <img src="/assets/images/setting.png" class="img-fluid" alt="Responsive image">
</div>

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
            <table id="settingTable" 
                class="table table-striped data-table-setting my-2">
                <thead>
                    <tr>
                        <th class="text-center">Program</th>
                        <th class="text-center">English Description</th>
                        <th class="text-center">Arabic Description</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($settings as $setting)
                    <tr>
                        <td class="text-center">{{$setting->name}}</td>
                        <td>{{$setting->english_name}}</td>
                        <td>{{$setting->arabic_name}}</td>

                        <td class="text-center">
                            @if($setting->id == 1)
                                <a href="{{$setting->link}}" >
                                    <i class="bx bx-sm bx-bulb" style="color:orange"></i>
                                </a>
                            @else @if($setting->id == 2)
                                <a href="{{$setting->link}}" >
                                    <i class="bx bx-sm bx-droplet" style="color:blue"></i>
                                </a>
                            @else @if($setting->id == 3)
                                <a href="{{$setting->link}}" >
                                    <i class="bx bx-sm bx-wifi" style="color:red"></i>
                                </a>
                            @else @if($setting->id == 4)
                                <a href="{{$setting->link}}" >
                                    <i class="bx bx-sm bx-gas-pump" style="color:black"></i>
                                </a>
                            @endif
                            @endif
                            @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <a type="button" class="editSetting" id="editSetting" 
                                data-id="{{$setting->id}}">
                                <i class="fa-solid fa-edit text-success"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.setting.edit')

<script>

    var id = 0;

    // Update record
    $('#settingTable').on('click', '.editSetting', function() {
        id = $(this).data('id');

        // AJAX request
        $.ajax({
            url: 'setting/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {

                $('#updateSettingModal').modal('show');
                $('#link').val(response.link);
                $('#program').val(response.name);
                $('#englishDescription').val(response.english_name);
                $('#arabicDescription').val(response.arabic_name);
            }
        });
    });

    $('#saveSettingButton').on('click', function() {
            
        link = $('#link').val();
        program = $('#program').val();
        english_name = $('#englishDescription').val();
        arabic_name = $('#arabicDescription').val();

        $.ajax({
            url: 'setting/edit_setting/' + id,
            type: 'get',
            data: {
                id: id,
                english_name: english_name,
                arabic_name: arabic_name,
                link: link,
                program: program
            }, 
            dataType: 'json',
            success: function(response) {

                $('#updateSettingModal').modal('toggle');
                $('#closeSettingUpdate').click ();

                if(response == 1) {
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Setting Updated Successfully!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay!'
                    }).then((result) => {
                       
                        window.location.reload();
                    });
                }
            }
        });
    });
</script>
@endsection