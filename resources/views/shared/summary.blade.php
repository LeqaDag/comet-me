<div class="card mb-4">
  <div class="card-body">
    <h5>Served Communities Energy
       
    </h5>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row">
        <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$regionNumbers}}</h2>
              <span class="text-muted">Regions</span>
              <div class="primary">
                <a href="{{'sub-region'}}" target="_blank" type="button">
                  <i class="bx bx-map me-1 bx-lg text-warning"></i>
                </a>
              </div>
            </div>
          </div> 
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$communityNumbers}}</h2>
              <span class="text-muted">Communitites</span>
              <div class="">
                <a href="{{'community'}}" target="_blank" type="button">
                  <i class="bx bx-home me-1 bx-lg text-success"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$householdNumbers}}</h2>
              <span class="text-muted">Households</span>
              <div class="primary">
                <a href="{{'household'}}" target="_blank" type="button">
                  <i class="bx bx-user me-1 bx-lg bx-primary"></i>
                </a>
              </div>
            </div>
          </div> 
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfPeople->number_of_people}}</h2>
              <span class="text-muted">People</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-group me-1 bx-lg text-dark"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfMale->number_of_male}}</h2>
              <span class="text-muted">Male</span>
              <div class="">
                <a href="#" type="button">
                  <i class="bx bx-male me-1 bx-lg text-secondary"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfFemale->number_of_female}}</h2>
              <span class="text-muted">Female</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-female me-1 bx-lg text-light"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfAdults->number_of_adults}}</h2>
              <span class="text-muted">Adults</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-male bx-lg text-danger"></i>
                  <i class="bx bx-female me-1 bx-lg text-danger"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfChildren->number_of_children}}</h2>
              <span class="text-muted">Children</span>
              <div class="">
                <a href="#" type="button">
                  <i class="bx bx-face me-1 bx-lg text-info"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalMgSystem->count()}}</h2>
              <span class="text-muted">MG Systems</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button">
                  <i class="bx bx-grid me-1 bx-lg text-success"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalFbsSystem->count()}}</h2>
              <span class="text-muted">FBS Systems</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button"> 
                  <i class="bx bx-sun me-1 bx-lg text-warning"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalRatedPower}}</h2>
              <span class="text-muted">Total Rated Power (KW)</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button"> 
                  <i class="bx bx-wind me-1 bx-lg" style="color:yellow"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <!-- H2O Users -->
  <div class="row mb-4">
    <div class="col-md-12 col-lg-12">
      <div class="card"> 
        <div class="card-header">
          <h5 class="card-title mb-0">Water Users</h5>
        </div>
        <div class="card-body pb-2">
          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <ul class="p-0 m-0">
              <li class="d-flex mb-4 pb-2">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <a href="{{'all-water'}}" target="_blank" type="button"> 
                      <i class='bx bx-water'></i>
                    </a>
                  </span>
                </div>
                <div class="d-flex flex-column w-100">
                  <div class="d-flex justify-content-between mb-1">
                    <span>H2O Users</span>
                    <span class="text-muted"> 
                      {{$h2oUsersNumbers}}
                    </span>
                  </div>
                  <?php
                    $diff = ($h2oUsersNumbers/ $householdNumbers) * 100;
                  ?>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-primary" style="width: {{$diff}}%" 
                    role="progressbar" aria-valuenow="{{$diff}}" 
                    aria-valuemin="0" 
                    aria-valuemax="{{$householdNumbers}}"></div>
                  </div>
                </div>
              </li>
            
            </ul>
          </div>

          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <div class="d-flex justify-content-between align-items-center gap-3 w-100">
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$h2oNumber->sum}}</h5>
                  <small class="text-muted">H2O System</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet bx-large'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$gridLarge->sum}}</h5>
                  <small class="text-muted">Grid Integration Large</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$gridSmall->sum}}</h5>
                  <small class="text-muted">Grid Integration Small</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$waterNetworkUsers}}</h5>
                  <small class="text-muted">Water Network</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
    <!-- Internet Users -->
  <div class="row mb-4">
    <div class="col-lg-12 col-xl-12 col-md-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Internet Users</h5>
        </div>
        <div class="card-body pb-2">
          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <ul class="p-0 m-0">
              <li class="d-flex mb-4 pb-2">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <a href="{{'internet-user'}}" target="_blank" type="button"> 
                      <i class='bx bx-wifi'></i>
                    </a>
                  </span>
                </div>
                <div class="d-flex flex-column w-100">
                  <div class="d-flex justify-content-between mb-1">
                    <span>Internet Users</span>
                    <span class="text-muted">
                      {{$internetPercentage}} %
                    </span> 
                  </div>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-success" style="width: {{$internetPercentage}}%" 
                    role="progressbar" aria-valuenow="{{$internetPercentage}}" 
                    aria-valuemin="0" 
                    aria-valuemax="{{$allInternetPeople}}"></div>
                  </div>
                </div>
              </li>
            
            </ul>
          </div>
 
          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <div class="d-flex justify-content-between align-items-center gap-3 w-100">
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <a type="button" data-bs-toggle="modal" 
                      data-bs-target="#communityInternet">
                      <i class='bx bx-home'></i>
                    </a>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$activeInternetCommuntiiesCount->count()}}</h5>
                  <small class="text-muted">Active Communities</small>
                </div>
              </div>
              @include('employee.community.service.internet')
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-book-content bx-large'></i>
                  </span>
                </div>
                <div class="chart-info"> 
                  <h5 class="mb-0">{{$allContractHolders}}</h5>
                  <small class="text-muted">Contract Holders</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-user'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$allInternetUsersCounts}}</h5>
                  <small class="text-muted">Users</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-happy bx-large'></i>
                  </span>
                </div>
                <div class="chart-info"> 
                  <h5 class="mb-0">{{$youngInternetHolders}}</h5>
                  <small class="text-muted">Young Holders</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-buildings'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$InternetPublicCount}}</h5>
                  <small class="text-muted">Public Structures</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>