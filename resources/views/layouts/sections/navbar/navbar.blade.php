@php
$containerNav = $containerNav ?? 'container-fluid';
@endphp

<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="{{$containerNav}}">

    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
    @if(isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
      <a href="{{url('/')}}" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">
          @include('_partials.macros')
        </span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
      </a>

      @if(isset($menuHorizontal))
      <!-- Display menu close icon only for horizontal-menu with navbar-full -->
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
        <i class="bx bx-x bx-sm align-middle"></i>
      </a>
      @endif
    </div>
    @endif

    <!-- ! Not required for layout-without-menu -->
    @if(!isset($navbarHideToggle))
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>
    @endif

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

      @if(!isset($menuHorizontal))
      <!-- Search -->
      <!-- <div class="navbar-nav align-items-center">
        <div class="nav-item navbar-search-wrapper mb-0">
          <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
            <i class="bx bx-search-alt bx-sm"></i>
            <span class="d-none d-md-inline-block text-muted">Search </span>
          </a>
        </div>
      </div> -->
      <!-- /Search -->
      @endif

      <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- Download PDF -->
        <li class="nav-item me-2 me-xl-0">
          <a class="nav-link hide-arrow" title="Click here to download the manual!" 
            href="{{url('downloadPdf')}}">
            <i class='bx bx-sm bx-down-arrow-alt'></i>
          </a>
        </li>
        <!--/ Download PDF -->

        <!-- Style Switcher -->
        <li class="nav-item me-2 me-xl-0">
          <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
            <i class='bx bx-sm'></i>
          </a>
        </li>
        <!--/ Style Switcher -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <div class="avatar avatar-online">
            <a href="{{url('profile-user', Auth::guard('user')->user()->id)}}">
              @if(Auth::guard()) 
                @if(Auth::guard('user')->user()->image == "")
                  @if(Auth::guard('user')->user()->gender == "male")
                    <img  src="/assets/images/male.png" alt class="rounded-circle">
                  @else
                    <img src="/assets/images/female.png" alt class="rounded-circle">
                  @endif
                @else
                <img alt class="rounded-circle" src="{{url('users/profile/'.@Auth::guard('user')->user()->image)}}">
                @endif
              @endif
            </a>
          </div>
        </li>
        <!--/ User -->
      </ul>
    </div>
  </div>
</nav>
<!-- / Navbar -->