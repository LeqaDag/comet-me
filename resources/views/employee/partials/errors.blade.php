@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul class="{{trans('css.text-align')}} {{trans('css.direction')}} ">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif