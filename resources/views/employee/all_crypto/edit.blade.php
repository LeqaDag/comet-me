@extends('layouts.master')

@section('content')
@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.trade'),'url'=>url('trade')], 'action' =>$trade->loation])

@include('partials.errors')

  {!! Form::model($trade,['method'=>'PATCH','class'=>'form-horizontal',
    'files' => true, 'action'=>['TradeController@update', $trade->id]]) !!}
    @include('admin.trade.partials.form',['btn' =>trans('main.edit'), 'form' =>'editing'])
  {!! Form::close() !!}

@endsection


@section('js')
  @include('js.csrf')
@endsection



