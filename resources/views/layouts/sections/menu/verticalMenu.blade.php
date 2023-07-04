@php
$configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull)) 
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <img width=50 type="image/x-icon" src="{{('/logo.jpg')}}">
      <span class="app-brand-text demo menu-text fw-bold ms-2" style="font-size:18px">
        {{config('variables.templateName')}}
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
      <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
    </a>
  </div>
  @endif

  <!-- ! Hide menu divider if navbar-full -->
  @if(!isset($navbarFull))
    <div class="menu-divider mt-0 ">
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  @if(Auth::guard('user')->user()->user_type_id == 1)

    @include('layouts.sections.menu.admin.super_Admin')
  @else @if(Auth::guard('user')->user()->user_type_id == 2)

    @include('layouts.sections.menu.admin.admin')
  @else @if(Auth::guard('user')->user()->user_type_id == 3)

    @include('layouts.sections.menu.managers.energy')
  @else @if(Auth::guard('user')->user()->user_type_id == 4)

    @include('layouts.sections.menu.managers.energy')
  @else @if(Auth::guard('user')->user()->user_type_id == 5)

    @include('layouts.sections.menu.managers.water')
  @else @if(Auth::guard('user')->user()->user_type_id == 6)

    @include('layouts.sections.menu.managers.internet')
  @else @if(Auth::guard('user')->user()->user_type_id == 7)

    @include('layouts.sections.menu.managers.energy')
  @else @if(Auth::guard('user')->user()->user_type_id == 9)

    @include('layouts.sections.menu.managers.water')

  @else @if(Auth::guard('user')->user()->user_type_id == 11)

    @include('layouts.sections.menu.managers.water')
  @else @if(Auth::guard('user')->user()->user_type_id == 12)
 
    @include('layouts.sections.menu.managers.energy')
    
  @endif
  @endif
  @endif
  @endif
  @endif
  @endif
  @endif
  @endif
  @endif
  @endif
</aside>