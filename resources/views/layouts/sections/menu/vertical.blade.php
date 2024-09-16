
<style>
    .menu-sub .menu-sub {
        margin-left: 20px; /* Adjust the value as needed */
    }
</style>
<ul class="menu-inner py-1" id="menu">

    <li class="menu-item" id="home">
        <a href="{{url('home')}}" class="dashboard menu-link" >
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Dashboards</div>
        </a>
    </li>

    @if(Auth::guard('user')->user()->user_type_id == 1 ||
        Auth::guard('user')->user()->user_type_id == 2)
    <li class="menu-item" id="work-plans">
        <a href="{{url('work-plan')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-check"></i>
            <div>Action Items</div>
        </a>
    </li>
    @endif
    
    <li class="menu-item" id="action-items">
        <a href="{{url('action-item')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-task"></i>
            <div>Project Plans</div>
        </a>
    </li>

    <li class="menu-item" id="all-active">
        <a href="{{url('all-active')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-show"></i>
            <div>Overview of Active Users</div>
        </a>
    </li>

    <li class="menu-item" id="communities">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home"></i>
            <div>Communities</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="all-community">
                <a href="{{url('community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>All</div>
                </a>
            </li>
            <li class="menu-item" id="served-community">
                <a href="{{url('served-community')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Served</div>
                </a>
            </li>
            <li class="menu-item" id="in_progress_communities">
                <a class="menu-link menu-toggle">
                    <div>In Progress</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="initial-community">
                        <a href="{{url('initial-community')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Initial Survey</div>
                        </a>
                    </li>
                    <li class="menu-item" id="ac-community">
                        <a href="{{url('ac-community')}}" class="menu-link" >
                            <i class=""></i>
                            <div>AC in Progress</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item" id="representative">
                <a href="{{url('representative')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Community Representatives</div>
                </a>
            </li>
            <li class="menu-item" id="sub-community-household">
                <a href="{{url('sub-community-household')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Sub Communities</div>
                </a>
            </li>
            <li class="menu-item" id="community-compound">
                <a href="{{url('community-compound')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Community Compounds</div>
                </a>
            </li>
        </ul>
    </li>
    <li class="menu-item" id="households">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div>Households</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="household">
                <a href="{{url('household')}}" class="menu-link" >
                    <i class=""></i>
                    <div>All</div>
                </a>
            </li>
            @if(Auth::guard('user')->user()->user_type_id == 1 || 
                Auth::guard('user')->user()->user_type_id == 2 || 
                Auth::guard('user')->user()->user_type_id == 3 || 
                Auth::guard('user')->user()->user_type_id == 4)
                <li class="menu-item" id="requested-household">
                    <a href="{{url('requested-household')}}" class="menu-link" >
                        <i class=""></i>
                        <div>Requested System/Meter</div>
                    </a>
                </li>
            @endif
            <li class="menu-item" id="in_progress_households">
                <a class="menu-link menu-toggle">
                    <div>In Progress</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="initial-household">
                        <a href="{{url('initial-household')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Initial Survey</div>
                        </a>
                    </li>
                    <li class="menu-item" id="ac-household">
                        <a href="{{url('ac-household')}}" class="menu-link" >
                            <i class=""></i>
                            <div>AC Survey Completed</div>
                        </a>
                    </li> 
                    <li class="menu-item" id="progress-household">
                        <a href="{{url('progress-household')}}" class="menu-link" >
                            <i class=""></i>
                            <div>AC Completed</div>
                        </a>
                    </li>
                    <li class="menu-item" id="hold-household">
                        <a href="{{url('hold-household')}}" class="menu-link" >
                            <i class=""></i>
                            <div>On Hold</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item" id="served-household">
                <a href="{{url('served-household')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Served</div>
                </a>
            </li>
            <li class="menu-item" id="displaced-household">
                <a href="{{url('displaced-household')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Displaced</div>
                </a>
            </li>
        </ul>
    </li>
   
    <li class="menu-item" id="public-structure">
        <a href="{{url('public-structure')}}" class="dashboard menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i>
            <div>Public Structures</div>
        </a>
    </li>

    <li class="menu-item" id="requested-tab">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-hive"></i>
            <div>Requested Systems</div>
        </a>
        <ul class="menu-sub">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2)
                <li class="menu-item" id="energy-request">
                    <a href="{{url('energy-request')}}" class="menu-link" >
                        <i class=""></i>
                        <div>Requested Energy</div>
                    </a>
                </li>
            @endif 
            <li class="menu-item" id="water-request">
                <a href="{{url('water-request')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Requested Water</div>
                </a>
            </li> 
        </ul>
    </li>
 
    <li class="menu-item" id="services">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div>Services</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="energy-service">
                <a class="menu-link menu-toggle">
                    <div>Energy </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="all-meter">
                        <a href="{{url('all-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Meter Holders</div>
                        </a>
                    </li>
                    <li class="menu-item" id="household-meter">
                        <a href="{{url('household-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Shared Users</div>
                        </a>
                    </li>
                    <li class="menu-item" id="energy-public">
                        <a href="{{url('energy-public')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Public Structures Meters</div>
                        </a>
                    </li>
                    <li class="menu-item" id="comet-meter">
                        <a href="{{url('comet-meter')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Comet Meters</div>
                        </a>
                    </li>
                    <li class="menu-item" id="refrigerator-user">
                        <a href="{{url('refrigerator-user')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Refrigerator Holders</div>
                        </a>
                    </li>
                    <li class="menu-item" id="vendor">
                        <a href="{{url('vending-point')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Vending Points</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item" id="water-service">
                <a class="menu-link menu-toggle">
                    <div>Water </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="all-water">
                        <a href="{{url('all-water')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Holders</div>
                        </a>
                    </li>
                    <li class="menu-item" id="shared-h2o">
                        <a href="{{url('shared-h2o')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Shared H2O Users</div>
                        </a>
                    </li>
                    <li class="menu-item" id="water-public">
                        <a href="{{url('water-public')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Shared H2O Public Structures</div>
                        </a>
                    </li>
                    <li class="menu-item" id="shared-grid">
                        <a href="{{url('shared-grid')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Shared Grid Holders</div>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="menu-item" id="internet-service">
                <a class="menu-link menu-toggle">
                <!--  <i class="menu-icon tf-icons bx bx-wifi"></i>-->
                    <div>Internet </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="internet-user">
                        <a href="{{url('internet-user')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Contract Holders</div>
                        </a>
                    </li>
                </ul>
            </li> 

            <li class="menu-item" id="camera-service">
                <a class="menu-link menu-toggle">
                <!--  <i class="menu-icon tf-icons bx bx-wifi"></i>-->
                    <div>Cameras </div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="camera">
                        <a href="{{url('camera')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Installed</div>
                        </a>
                    </li>
                </ul>
                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 6 ||
                    Auth::guard('user')->user()->user_type_id == 10)
                    <ul class="menu-sub">
                        <li class="menu-item" id="camera-component">
                            <a href="{{url('camera-component')}}" class="menu-link" >
                                <i class=""></i>
                                <div>Camera Components</div>
                            </a>
                        </li>
                    </ul>
                @endif
            </li> 
        </ul>
    </li>
 
    <li class="menu-item" id="maintenance">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-extension"></i>
            <div>Maintenance and Monitoring</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="energy-maintenance-tab">
                <a class="menu-link menu-toggle">
                    <div>Electricity Maintenance</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="energy-maintenance">
                        <a href="{{url('energy-maintenance')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Maintenance</div>
                        </a>
                    </li>
                    <li class="menu-item" id="energy-issue">
                        <a href="{{url('energy-issue')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Issues</div>
                        </a>
                    </li> 
                    <li class="menu-item" id="energy-action">
                        <a href="{{url('energy-action')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Actions</div>
                        </a>
                    </li>
                    @if(Auth::guard('user')->user()->user_type_id == 1 ||
                        Auth::guard('user')->user()->user_type_id == 2 ||
                        Auth::guard('user')->user()->user_type_id == 4)
                        <li class="menu-item" id="energy-generator-turbine">
                            <a href="{{url('energy-generator-turbine')}}" class="menu-link" >
                                <i class=""></i>
                                <div>Generators/Turbines</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="menu-item" id="refrigerator-maintenance">
                <a href="{{url('refrigerator-maintenance')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Refrigerator Maintenance</div>
                </a>
            </li>

            
            <li class="menu-item" id="water-maintenance-tab">
                <a class="menu-link menu-toggle">
                    <div>Water Maintenance</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="water-maintenance">
                        <a href="{{url('water-maintenance')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Maintenance</div>
                        </a>
                    </li>
                    <li class="menu-item" id="water-action">
                        <a href="{{url('water-action')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Actions</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item" id="internet-maintenance-tab">
                <a class="menu-link menu-toggle">
                    <div>Internet Maintenance</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="internet-maintenance">
                        <a href="{{url('internet-maintenance')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Maintenance</div>
                        </a>
                    </li>
                    <li class="menu-item" id="internet-issue">
                        <a href="{{url('internet-issue')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Issues</div>
                        </a>
                    </li> 
                    <li class="menu-item" id="internet-action">
                        <a href="{{url('internet-action')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Actions</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item" id="energy-safety">
                <a href="{{url('energy-safety')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Meters Safety Check</div>
                </a>
            </li>
            <li class="menu-item" id="results">
                <a class="menu-link menu-toggle">
                    <!-- <i class="menu-icon tf-icons bx bx-receipt"></i> -->
                    <div>Water Quality Results</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="water-summary">
                        <a href="{{url('water-summary')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Summary</div>
                        </a>
                    </li>
                    <li class="menu-item" id="quality-result">
                        <a href="{{url('quality-result')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Results</div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="menu-item" id="systems">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-data"></i>
            <div>Systems</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="energy">
                <a class="menu-link menu-toggle">
                    <div>Energy</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="energy-system">
                        <a href="{{url('energy-system')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Energy System</div>
                        </a>
                    </li>
                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2)
                    <li class="menu-item" id="energy-cost">
                        <a href="{{url('energy-cost')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Energy Cost</div>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('user')->user()->user_type_id == 1)
                    <li class="menu-item" id="donor-cost">
                        <a href="{{url('donor-cost')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Donor Fund</div>
                        </a>
                    </li>
                @endif
                </ul>
            </li>
            <li class="menu-item" id="water-system-tab">
                <a class="menu-link menu-toggle">
                    <i class=""></i>
                    <div>Water System</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="water-system">
                        <a href="{{url('water-system')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All water systems</div>
                        </a> 
                    </li>
                    <li class="menu-item" id="water-log">
                        <a href="{{url('water-log')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Water logframe</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item" id="internet-system-tab">
                <a class="menu-link menu-toggle">
                    <div>Internet System</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="internet-system">
                        <a href="{{url('internet-system')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Internet Systems</div>
                        </a>
                    </li>
                    <!-- <li class="menu-item" id="internet-cluster">
                        <a href="{{url('internet-cluster')}}" class="menu-link" >
                            <i class=""></i>
                            <div>All Internet Clusters</div>
                        </a>
                    </li> -->
                </ul>
            </li> 
        </ul>
    </li>

    
    <li class="menu-item" id="incidents">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-error-alt"></i>
            <div>Incidents</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="mg-incident">
                <a href="{{url('mg-incident')}}" class="menu-link" >
                    <i class=""></i>
                    <div>MG System</div>
                </a>
            </li>
            <li class="menu-item" id="fbs-incident">
                <a href="{{url('fbs-incident')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Energy Users</div>
                </a>
            </li>
            <li class="menu-item" id="water-incident">
                <a href="{{url('water-incident')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Water Holders</div>
                </a>
            </li>
            <li class="menu-item" id="internet-incidents">
                <a class="menu-link menu-toggle">
                    <div>Internet Incidents</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item" id="incident-network">
                        <a href="{{url('incident-network')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Network</div>
                        </a>
                    </li>
                    <li class="menu-item" id="incident-internet-user">
                        <a href="{{url('incident-internet-user')}}" class="menu-link" >
                            <i class=""></i>
                            <div>Internet Users</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item" id="incident-camera">
                <a href="{{url('incident-camera')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Cameras</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item" id="regions">
        <a class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-map"></i>
            <div>Regions</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item" id="region">
                <a href="{{url('region')}}" class="menu-link" >
                    <i class=""></i>
                    <div>All Regions</div>
                </a>
            </li>
            <li class="menu-item" id="sub-region">
                <a href="{{url('sub-region')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Sub Regions</div>
                </a>
            </li>
            <li class="menu-item" id="sub-sub-region">
                <a href="{{url('sub-sub-region')}}" class="menu-link" >
                    <i class=""></i>
                    <div>Sub Sub Regions</div>
                </a>
            </li>
        </ul>
    </li>
    
    @if(Auth::guard('user')->user()->user_type_id == 1 ||
        Auth::guard('user')->user()->user_type_id == 2)
    <li class="menu-item" id="donor">
        <a href="{{url('donor')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-money"></i>
            <div>Donors</div>
        </a>
    </li>
    <!-- <li class="menu-item" id="chart">
        <a href="{{url('chart')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-chart"></i>
            <div>Charts</div>
        </a>
    </li> -->
    <li class="menu-item" id="user">
        <a href="{{url('user')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div>Users</div>
        </a>
    </li>
    <li class="menu-item" id="setting">
        <a href="{{url('setting')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div>Settings</div>
        </a>
    </li>
    @endif
</ul>

<script>

    $(".menu-inner li").on("click", function() {
      $("li").removeClass("active");
      $(this).addClass("active");
    });

    var activeClass = null;
    var url = window.location.href; 
    parts = url.split("/"),
    last_part = parts[parts.length-1];

    if(last_part == "home") {

        $("#home").addClass("active");

    } else if(last_part == "all-active") {

        $("#all-active").addClass("active");

    }  else if(last_part == "work-plan") {

        $("#work-plans").addClass("active");

    } else if(last_part == "action-item") {

        $("#action-items").addClass("active");

    } else if(last_part == "region") {

        $("#region").addClass("active");
        $("#regions").addClass("open");
    } else if(last_part == "sub-region") {

        $("#sub-region").addClass("active");
        $("#regions").addClass("open");
    } else if(last_part == "sub-sub-region") {

        $("#sub-sub-region").addClass("active");
        $("#regions").addClass("open");
    } else if(last_part == "community") {

        $("#all-community").addClass("active");
        $("#communities").addClass("open");
    } else if(last_part == "initial-community") {

        $("#initial-community").addClass("active");
        $("#in_progress_communities").addClass("active");
        $("#communities").addClass("open");
        $("#in_progress_communities").addClass("open");
    } else if(last_part == "ac-community") {

        $("#ac-community").addClass("active");
        $("#in_progress_communities").addClass("active");
        $("#communities").addClass("open");
        $("#in_progress_communities").addClass("open");
    } else if(last_part == "served-community") {

        $("#served-community").addClass("active");
        $("#communities").addClass("open");
    } else if(last_part == "representative") {

        $("#representative").addClass("active");
        $("#communities").addClass("open");
    } else if(last_part == "sub-community-household") {
        
        $("#sub-community-household").addClass("active");
        $("#communities").addClass("open");
    } else if(last_part == "community-compound") {
        
        $("#community-compound").addClass("active");
        $("#communities").addClass("open");
    } else if(last_part == "household") {

        $("#household").addClass("active");
        $("#households").addClass("open");
    } else if(last_part == "requested-household") {

        $("#requested-household").addClass("active");
        $("#households").addClass("open");
    } else if(last_part == "initial-household") {

        $("#initial-household").addClass("active");
        $("#households").addClass("open");
        $("#in_progress_households").addClass("active");
        $("#in_progress_households").addClass("open");
    } else if(last_part == "ac-household") {

        $("#ac-household").addClass("active");
        $("#households").addClass("open");
        $("#in_progress_households").addClass("active");
        $("#in_progress_households").addClass("open");
    } else if(last_part == "progress-household") {

        $("#progress-household").addClass("active");
        $("#households").addClass("open");
        $("#in_progress_households").addClass("active");
        $("#in_progress_households").addClass("open");
    }  else if(last_part == "hold-household") {

        $("#hold-household").addClass("active");
        $("#households").addClass("open");
        $("#in_progress_households").addClass("active");
        $("#in_progress_households").addClass("open");
    } else if(last_part == "served-household") {

        $("#served-household").addClass("active");
        $("#households").addClass("open");
    }  else if(last_part == "displaced-household") {

        $("#displaced-household").addClass("active");
        $("#households").addClass("open");
    } else if(last_part == "public-structure") {

        $("#public-structure").addClass("active");
    } else if(last_part == "all-meter") {

        $("#all-meter").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "energy-request") {
        
        $("#requested-tab").addClass("open");
        $("#energy-request").addClass("active");
    } else if(last_part == "water-request") {

        $("#requested-tab").addClass("open");
        $("#water-request").addClass("active");
    } else if(last_part == "household-meter") {

        $("#household-meter").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "energy-public") {

        $("#energy-public").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "comet-meter") {

        $("#comet-meter").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "refrigerator-user") {

        $("#refrigerator-user").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "vendor") {

        $("#vendor").addClass("active");
        $("#energy-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "all-water") {

        $("#all-water").addClass("active");
        $("#water-service").addClass("open");
        $("#services").addClass("open");
    }  else if(last_part == "shared-h2o") {

        $("#shared-h2o").addClass("active");
        $("#water-service").addClass("open");
        $("#services").addClass("open"); 
    } else if(last_part == "water-public") {

        $("#water-public").addClass("active");
        $("#water-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "shared-grid") {

        $("#shared-grid").addClass("active");
        $("#water-service").addClass("open");
        $("#services").addClass("open");
    }  else if(last_part == "camera-component") {

        $("#camera-component").addClass("active");
        $("#internet-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "internet-user") {

        $("#internet-user").addClass("active");
        $("#internet-service").addClass("open");
        $("#services").addClass("open");
    }  else if(last_part == "camera") {

        $("#camera").addClass("active");
        $("#camera-service").addClass("open");
        $("#services").addClass("open");
    } else if(last_part == "energy-maintenance") {

        $("#energy-maintenance").addClass("active");
        $("#maintenance").addClass("open");
        $("#energy-maintenance-tab").addClass("active");
        $("#energy-maintenance-tab").addClass("open");
    }   else if(last_part == "energy-issue") {

        $("#energy-issue").addClass("active");
        $("#maintenance").addClass("open");
        $("#energy-maintenance-tab").addClass("active");
        $("#energy-maintenance-tab").addClass("open");
    }   else if(last_part == "energy-action") {

        $("#energy-action").addClass("active");
        $("#maintenance").addClass("open");
        $("#energy-maintenance-tab").addClass("active");
        $("#energy-maintenance-tab").addClass("open");
    }  else if(last_part == "energy-generator-turbine") {

        $("#energy-generator-turbine").addClass("active");
        $("#maintenance").addClass("open");
        $("#energy-maintenance-tab").addClass("active");
        $("#energy-maintenance-tab").addClass("open");
    }  else if(last_part == "refrigerator-maintenance") {

        $("#refrigerator-maintenance").addClass("active");
        $("#maintenance").addClass("open");
    } else if(last_part == "water-maintenance") {

        $("#water-maintenance").addClass("active");
        $("#maintenance").addClass("open");
        $("#water-maintenance-tab").addClass("active");
        $("#water-maintenance-tab").addClass("open");
    }  else if(last_part == "water-action") {

        $("#water-action").addClass("active");
        $("#maintenance").addClass("open");
        $("#water-maintenance-tab").addClass("active");
        $("#water-maintenance-tab").addClass("open");
    } else if(last_part == "internet-maintenance") {

        $("#internet-maintenance").addClass("active");
        $("#maintenance").addClass("open");
        $("#internet-maintenance-tab").addClass("active");
        $("#internet-maintenance-tab").addClass("open");
    }   else if(last_part == "internet-issue") {

        $("#internet-issue").addClass("active");
        $("#maintenance").addClass("open");
        $("#internet-maintenance-tab").addClass("active");
        $("#internet-maintenance-tab").addClass("open");
    }   else if(last_part == "internet-action") {

        $("#internet-action").addClass("active");
        $("#maintenance").addClass("open");
        $("#internet-maintenance-tab").addClass("active");
        $("#internet-maintenance-tab").addClass("open");
    } else if(last_part == "energy-safety") {

        $("#energy-safety").addClass("active");
        $("#maintenance").addClass("open");
    } else if(last_part == "water-summary") {

        $("#water-summary").addClass("active");
        $("#maintenance").addClass("open");
        $("#results").addClass("open");
    } else if(last_part == "quality-result") {

        $("#quality-result").addClass("active");
        $("#maintenance").addClass("open");
        $("#results").addClass("open");
    } else if(last_part == "energy-system") {

        $("#energy-system").addClass("active");
        $("#energy").addClass("open");
        $("#systems").addClass("open");
    }  else if(last_part == "energy-cost") {

        $("#energy-cost").addClass("active");
        $("#energy").addClass("open");
        $("#systems").addClass("open");
    }  else if(last_part == "donor-cost") {

        $("#donor-cost").addClass("active");
        $("#energy").addClass("open");
        $("#systems").addClass("open");
    } else if(last_part == "water-system") {

        $("#water-system").addClass("active");
        $("#water-system-tab").addClass("open");
        $("#systems").addClass("open");
    } else if(last_part == "water-log") {

        $("#water-log").addClass("active");
        $("#water-system-tab").addClass("open");
        $("#systems").addClass("open");
    } else  if(last_part == "internet-system") {

        $("#internet-system").addClass("active");
        $("#internet-system-tab").addClass("open");
        $("#systems").addClass("open");
    } else if(last_part == "internet-cluster") {

        $("#internet-cluster").addClass("active");
        $("#internet-system-tab").addClass("open");
        $("#systems").addClass("open");
    } else if(last_part == "mg-incident") {

        $("#mg-incident").addClass("active");
        $("#incidents").addClass("open");
    } else if(last_part == "fbs-incident") {

        $("#fbs-incident").addClass("active");
        $("#incidents").addClass("open");
    } else if(last_part == "water-incident") {

        $("#water-incident").addClass("active");
        $("#incidents").addClass("open");
    } else if(last_part == "donor") {

        $("#donor").addClass("active");
    } else if(last_part == "user") {

        $("#user").addClass("active");
    } else if(last_part == "chart") {

        $("#chart").addClass("active");
    } else if(last_part == "setting") {

        $("#setting").addClass("active");
    } else if(last_part == "incident-network") {

        $("#incident-network").addClass("active");
        $("#internet-incidents").addClass("open");
        $("#incidents").addClass("open");
    } else if(last_part == "incident-internet-user") {

        $("#incident-internet-user").addClass("active");
        $("#internet-incidents").addClass("open");
        $("#incidents").addClass("open");
    } else if(last_part == "incident-camera") {

        $("#incident-camera").addClass("active");
        $("#incidents").addClass("open");
    }
</script>