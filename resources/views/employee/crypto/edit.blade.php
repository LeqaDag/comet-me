@extends('layouts.home_employee')

@section('content')
    @include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
     'url'=>url('employee-crypto')], 'action' =>trans('main.edit')])
    @include('partials.errors')

    @include('employee.crypto.partials.edit')

    
@endsection



