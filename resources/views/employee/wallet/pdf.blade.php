


@extends('pdf.layout')

@section('main')

	<h1>{{trans('main.regions')}}</h1>

	@if (count($regions))
<table  cellspacing="6" cellpadding="4" style="width: 100%" >
	<tr bgcolor="#cccccc"  >
		<td>{{trans('main.id')}}</td>
		<td>{{trans('main.name')}}</td>
		<td>{{trans('main.parent')}}</td>
		<td>{{trans('main.named')}}</td>
		<td>{{trans('main.num_of_users')}}</td>
		<td>{{trans('main.created_at')}}</td>
	</tr>
	@foreach ($regions as $region)
	<tr cellspacing="0">
		<td>{{$region->id}}</td>
		<td>{{$region->name}}</td>
		<td>{{$region->parent_name}}</td>
		<td>{{trans('main.regions_level.'.$region->level)}}</td>
		<td>{{$region->num_of_users}}</td>
		<td>{{ date('Y-m-d',strtotime($region->created_at))}}</td>
	</tr>
			
		@endforeach
		<tr bgcolor="#cccccc" >
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>

</div>
	@endif


@endsection
