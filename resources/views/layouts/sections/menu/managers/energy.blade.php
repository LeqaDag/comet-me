<ul class="menu-inner py-1">

    <li class="menu-item">
        <a href="{{url('home')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Dashboards</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="{{url('all-active')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-show"></i>
            <div>Overview of Active Users</div>
        </a>
    </li>

    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-map"></i>
            <div>Regions</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
            <a href="{{url('region')}}" class="menu-link" >
                <i class=""></i>
                <div>All Regions</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{url('sub-region')}}" class="menu-link" >
                <i class=""></i>
                <div>Sub Regions</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{url('sub-sub-region')}}" class="menu-link" >
                <i class=""></i>
                <div>Sub Sub Regions</div>
            </a>
            </li>
        </ul>
    </li>
    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home"></i>
            <div>Communities</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{url('community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>All</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('initial-community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Initial Survey</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('ac-community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>AC Survey</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('served-community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Served</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('representative')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Community Representatives</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('sub-community-household')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Sub Communities</div>
                </a>
            </li>
        </ul>
    </li>
    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div>Households</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
            <a href="{{url('household')}}" class="menu-link" >
                <i class=""></i>
                <div>All</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{url('initial-household')}}" class="menu-link" >
                <i class=""></i>
                <div>Initial Survey</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{url('ac-household')}}" class="menu-link" >
                <i class=""></i>
                <div>AC Survey</div>
            </a>
            </li>
            <li class="menu-item">
            <a href="{{url('served-household')}}" class="menu-link" >
                <i class=""></i>
                <div>Served</div>
            </a>
            </li>
        </ul>
    </li>
   
    
    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Services</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a class="menu-link menu-toggle">
                    <div>Energy </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{url('all-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Meter Holders</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{url('household-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Shared Users</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{url('energy-public')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Public Structures Meters</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{url('comet-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Comet Meters</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{url('refrigerator-user')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Refrigerator Holders</div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
 
    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-extension"></i>
            <div>Maintenance and Monitoring</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{url('energy-maintenance')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Electricity Maintenance</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('refrigerator-maintenance')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Refrigerator Maintenance</div>
                </a>
            </li>
        </ul>
    </li>
    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-data"></i>
            <div>Systems</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{url('energy-system')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Energy System</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-error-alt"></i>
            <div>Incidents</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{url('mg-incident')}}" class="menu-link" >
                    <i class=""></i>
                    <div>MG System</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{url('fbs-incident')}}" class="menu-link" >
                    <i class=""></i>
                    <div>FBS Users</div>
                </a>
            </li>
        </ul>
    </li>

    
    <li class="menu-item">
        <a href="{{url('donor')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-money"></i>
            <div>Donors</div>
        </a>
    </li>
</ul>