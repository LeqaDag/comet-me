@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'image communities')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">{{$community->english_name}} </span> Images
</h4>

    
<div class="container">
    <div class="card my-2">
        <div class="card-body">       
            <form action="{{url('photo')}}" enctype='multipart/form-data' method="post">    
                @csrf
                <div class="row">
                    <input type="hidden" name="community_id" value="{{$community->id}}">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <input type='file' name='photos[]' multiple 
                                class="form-control" >
                        </fieldset>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">
                        Submit
                    </button>
                </div>  
            </form>
            <div class="row">
                <div class="col-md">
                    <div id="communityImage{{$community->id}}" class="carousel slide" data-bs-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach(\App\Models\Photo::where('community_id', '=', $community->id)->get() as $key => $data)
                                <li data-target="#communityImage{{$community->id}}" 
                                    data-slide-to="{{ $loop->index }}" 
                                    class="{{ $loop->first ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach(\App\Models\Photo::where('community_id', '=', $community->id)->get() as $key => $data)
                                <div class="carousel-item item {{ $loop->first ? ' active' : '' }}" >
                                    <a href="">
                                        <img src="{{url('/communities/images/'.$data->slug)}}"
                                         class="d-block w-100" >
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        
                        <a class="carousel-control-prev" href="#communityImage{{$community->id}}" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#communityImage{{$community->id}}" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

</script>
@endsection