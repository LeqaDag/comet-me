@extends('layouts.home_employee')

@section('content')
    @include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
     'url'=>url('employee-crypto')], 'action' =>trans('main.create')])
    @include('partials.errors')

    {!! Form::open(['url' => 'employee-crypto', 'enctype' => "multipart/form-data", 'class'=> 'form-horizontal']) !!}
        @include('employee.crypto.partials.form',['btn' =>trans('main.create'), 'form' =>'adding'])
    {!! Form::close() !!}

@endsection
    





