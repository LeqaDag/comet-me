@extends('layouts.home_employee')

@section('content')
    @include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
     'url'=>url('employee-crypto')], 'action' =>trans('main.create')])
    @include('partials.errors')


    <form action="{{ url('employee-crypto') }}" method="POST" enctype="multipart/form-data"
        class="form-horizontal" id="createRecoForm">
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        @include('employee.crypto.partials.form')
    </form>
    
@endsection
    





