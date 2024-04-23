<!-- Action Items for adding english name for the internet contract holders -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-bulb"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-left">
        <h6 class="card-header"></h6>
        <div class="card-body">
            <div class="mb-4">
                <h6>Installation Year</h6>
                @if(count($missingEnergySystemInstallationYear) > 0)
                    <p>Add the 
                        <span> missing Installation year </span> 
                        for these energy systems: 
                    </p>
                    @foreach($missingEnergySystemInstallationYear as $missingEnergySystemYear)
                    <ul class="list-unstyled">
                        <li class="d-flex" style="margin-top:9px">
                            <a class="btn btn-warning btn-sm" type="button" 
                                href="/energy-system/{{$missingEnergySystemYear->id}}/edit" target="_blank">
                                <span> {{$missingEnergySystemYear->name}} </span>   
                            </a>
                        </li>
                    </ul>
                    @endforeach
                @else
                    <span>All is well!</span>
                @endif
            </div>

            <hr>
            <div class="mb-4">
                <h6>Cycle Year</h6>
                @if(count($missingEnergySystemCycleYear) > 0)
                <p>Add the 
                    <span> missing cycle year </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemCycleYear as $missingEnergySystemCycle)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemCycle->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemCycle->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @else
                    <span>All is well!</span>
                @endif
            </div>

            <hr>
            <div class="mb-4">
                <h6>Rated Power</h6>
                @if(count($missingEnergySystemRatedPower) > 0)
                <p>Add the 
                    <span> missing rated power </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemRatedPower as $missingEnergySystemRated)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemRated->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemRated->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @else
                    <span>All is well!</span>
                @endif
            </div>

            <hr>
            <div class="mb-4">
                <h6>Generated Power</h6>
                @if(count($missingEnergySystemGeneratedPower) > 0)
                <p>Add the 
                    <span> missing generated power </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemGeneratedPower as $missingEnergySystemGenerated)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemGenerated->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemGenerated->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @else
                    <span>All is well!</span>
                @endif
            </div>

            <hr>
            <div class="mb-4">
                <h6>Turbine Power</h6>
                @if(count($missingEnergySystemTurbinePower) > 0)
                <p>Add the 
                    <span> missing turbine power </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemTurbinePower as $missingEnergySystemTurbine)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemTurbine->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemTurbine->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @else
                    <span>All is well!</span>
                @endif
            </div>

            <hr>
            <div class="mb-4">
                <h6>Energy Components</h6>
                @if(count($missingEnergySystemPv) > 0)
                <p>Add the 
                    <span> missing PV </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemPv as $missingEnergySystemSolar)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemSolar->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemSolar->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @endif

                <br>
                @if(count($missingEnergySystemBattery) > 0)
                <p>Add the 
                    <span> missing Batteries </span> 
                    for these energy systems: 
                </p>
                @foreach($missingEnergySystemBattery as $missingEnergySystemBatt)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/energy-system/{{$missingEnergySystemBatt->id}}/edit" target="_blank">
                            <span> {{$missingEnergySystemBatt->name}} </span>   
                        </a>
                    </li>
                </ul>
                @endforeach
                @endif

            </div>
        </div>
        <div class="timeline-event-time">Energy System</div>
    </div>
</li>