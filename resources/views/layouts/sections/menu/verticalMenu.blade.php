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

  @include('layouts.sections.menu.vertical')

</aside>