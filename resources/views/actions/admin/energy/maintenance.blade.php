<!-- Action Items for maintenance -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-right">
    <span class="timeline-indicator timeline-indicator-danger" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-cog"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header border-0 d-flex justify-content-between">
            <h5 class="card-title mb-0">Meter/Maintenance</h5>
        </div>
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-success" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-bulb"></i>
                        <a data-toggle="collapse" class="text-success" 
                            href="#notYetMeterActivationTab" 
                            aria-expanded="false" 
                            aria-controls="notYetMeterActivationTab">
                            Meter Activation Not Yet Completed 
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="notYetMeterActivationTab">

                <!-- add out of total users -->
                @if(count($newEnergyUsers) > 0)
                    <p>You've {{$newEnergyUsers->count()}} 
                        <a title="Export AC Households"
                            href="all-meter" target="_blank">
                            Energy Holders
                        </a>
                        where the meter is not yet activated.
                    </p>
                @endif
            </div>
            <!-- 
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-warning" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-bulb"></i>
                        <a data-toggle="collapse" class="text-warning" 
                            href="#ElectricityMaintenancesTab" 
                            aria-expanded="false" 
                            aria-controls="ElectricityMaintenancesTab">
                            Electricity Maintenances
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="ElectricityMaintenancesTab">

                @if(count($electricityNewMaintenances) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#electricityNewMaintenances">
                            <span class="text-warning">
                            {{$electricityNewMaintenances->count()}} NEW
                            </span>   
                        </a>
                        maintenance calls
                    </p> 
                    @include('actions.admin.maintenance.energy.new')
                @endif

                @if(count($electricityInProgressMaintenances) > 0)
                    <p>
                        You've got  
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#electricityInProgressMaintenances">
                            <span class="text-warning">
                            {{$electricityInProgressMaintenances->count()}} 
                            </span>   
                        </a>
                        maintenance actions that are still in progress.
                    </p> 
                    @include('actions.admin.maintenance.energy.in_progress')
                </p> 
                @endif
            </div> -->

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-danger" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-plug"></i>
                        <a data-toggle="collapse" class="text-danger" 
                            href="#SafteyCheckTab" 
                            aria-expanded="false" 
                            aria-controls="SafteyCheckTab">
                            Saftey Check
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="SafteyCheckTab">

                @if(count($notYetSafteyCheckedFbs) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#notYetSafteyCheckedFbs">
                            <span class="text-danger">
                            {{$notYetSafteyCheckedFbs->count()}} FBS
                            </span>   
                        </a>
                        not yet checked
                    </p> 
                    @include('actions.admin.maintenance.energy.saftey.fbs_not_checked')
                @endif 

                @if(count($notYetSafteyCompletedFbs) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#notYetSafteyCompletedFbs">
                            <span class="text-danger">
                            {{$notYetSafteyCompletedFbs->count()}} FBS
                            </span>   
                        </a>
                        not yet completed
                    </p> 
                    @include('actions.admin.maintenance.energy.saftey.fbs_not_completed')
                @endif

                @if(count($notYetSafteyCheckedMg) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#notYetSafteyCheckedMg">
                            <span class="text-danger">
                            {{$notYetSafteyCheckedMg->count()}} MG
                            </span>   
                        </a>
                        not yet checked
                    </p> 
                    @include('actions.admin.maintenance.energy.saftey.mg_not_checked')
                @endif

                @if(count($notYetCompletedSafteyCheckedMg) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#notYetCompletedSafteyCheckedMg">
                            <span class="text-danger">
                            {{$notYetCompletedSafteyCheckedMg->count()}} MG
                            </span>   
                        </a>
                        not yet completed
                    </p> 
                    @include('actions.admin.maintenance.energy.saftey.mg_not_completed')
                @endif
            </div>

            <!-- <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-primary" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-fridge"></i>
                        <a data-toggle="collapse" class="text-primary" 
                            href="#RefrigeratorMaintenancesTab" 
                            aria-expanded="false" 
                            aria-controls="RefrigeratorMaintenancesTab">
                            Refrigerator Maintenances
                        </a>
                    </div>
                </li>
            </ul>

            <div class="collapse multi-collapse container mb-4" 
                id="RefrigeratorMaintenancesTab">

                @if(count($refrigeratorNewMaintenances) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#refrigeratorNewMaintenances">
                            <span class="text-primary">
                            {{$refrigeratorNewMaintenances->count()}} NEW
                            </span>   
                        </a>
                        maintenance calls
                    </p> 
                    @include('actions.admin.maintenance.refrigerator.new')
                @endif


                @if(count($refrigeratorInProgressMaintenances) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#refrigeratorInProgressMaintenances">
                            <span class="text-primary">
                            {{$refrigeratorInProgressMaintenances->count()}} 
                            </span>   
                        </a>
                        maintenance actions that are still in progress.
                    </p> 
                    @include('actions.admin.maintenance.refrigerator.in_progress')
                @endif
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-info" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-droplet"></i>
                        <a data-toggle="collapse" class="text-info" 
                            href="#WaterMaintenancesTab" 
                            aria-expanded="false" 
                            aria-controls="WaterMaintenancesTab">
                            Water Maintenances
                        </a>
                    </div>
                </li>
            </ul>

            <div class="collapse multi-collapse container mb-4" 
                id="WaterMaintenancesTab">

                @if(count($waterNewMaintenances) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#waterNewMaintenances">
                            <span class="text-info">
                            {{$waterNewMaintenances->count()}} NEW
                            </span>   
                        </a>
                        maintenance calls
                    </p> 
                    @include('actions.admin.maintenance.water.new')
                @endif

                @if(count($waterInProgressMaintenances) > 0)
                    <p>
                        You've got 
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#waterInProgressMaintenances">
                            <span class="text-info">
                            {{$waterInProgressMaintenances->count()}} 
                            </span>   
                        </a>
                        maintenance actions that are still in progress.
                    </p> 
                    @include('actions.admin.maintenance.water.in_progress')
                @endif

            </div> -->
        </div>

        <div class="timeline-event-time">Maintenance</div>
    </div>
</li>