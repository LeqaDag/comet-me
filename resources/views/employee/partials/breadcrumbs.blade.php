<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-1">
        <h3 class="content-header-title">{{@$name}}</h3>
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/home')}}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item"><a href="{{$method['url']}}">{{$method['name']}}</a>
                </li>
                <li class="breadcrumb-item active">{{$action}}
                </li>
            </ol>
        </div>
    </div>
</div>