@php 
    $totalEnergyCost = 0; 

    $totalWaterCost = 0; 
    $totalInternetCost = 0; 
    $totalCameraCost = 0; 
@endphp

<div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
    <div class="mb-sm-0 mb-2">
        <p class="mb-0">Monetary Losses</p>
        <span class="text-muted">
            @if($allEnergyIncident)
                @if(count($allEnergyIncident->equipmentDamaged) > 0)
                    @foreach($allEnergyIncident->equipmentDamaged as $energyIncidentEquipment)
                        @php
                            $totalEnergyCost += ($energyIncidentEquipment->cost * $energyIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
                    
                @else @if(count($allEnergyIncident->damagedSystemEquipments) > 0)
                    @foreach($allEnergyIncident->damagedSystemEquipments as $equipment)
                        @php
                            $equipmentCost = ($equipment->cost ?? 0) * ($equipment->count ?? 1);
                            $totalEnergyCost += $equipmentCost;
                        @endphp
                    @endforeach
                @endif
                @endif
                <p class=" mt-2">{{ $totalEnergyCost }} ₪</p>
            @elseif($allWaterIncident)

                @if(count($allWaterIncident->equipmentDamaged) > 0)
                    @foreach($allWaterIncident->equipmentDamaged as $waterIncidentEquipment)
                        @php
                            $totalWaterCost += ($waterIncidentEquipment->cost * $waterIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
                    @else @if(count($allWaterIncident->damagedSystemEquipments) > 0)
                        @foreach($allWaterIncident->damagedSystemEquipments as $equipment)
                            @php
                                $equipmentCost = ($equipment->cost ?? 0) * ($equipment->count ?? 1);
                                $totalWaterCost += $equipmentCost;
                            @endphp
                        @endforeach
                    @endif
                @endif
                <p class=" mt-2 text-primary">{{ $totalWaterCost }} ₪</p>
            @elseif($allInternetIncident)

                @if(count($allInternetIncident->equipmentDamaged) > 0)
                    @foreach($allInternetIncident->equipmentDamaged as $internetIncidentEquipment)
                        @php
                            $totalInternetCost += ($internetIncidentEquipment->cost * $internetIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
                    @else @if(count($allInternetIncident->damagedSystemEquipments) > 0)
                        @foreach($allInternetIncident->damagedSystemEquipments as $equipment)
                            @php
                                $equipmentCost = ($equipment->cost ?? 0) * ($equipment->count ?? 1);
                                $totalInternetCost += $equipmentCost;
                            @endphp
                        @endforeach
                    @endif
                @endif
                <p class=" mt-2 text-primary">{{ $totalInternetCost }} ₪</p>
            @elseif($allCameraIncident)

                @if(count($allCameraIncident->equipmentDamaged) > 0)
                    @foreach($allCameraIncident->equipmentDamaged as $cameraIncidentEquipment)
                        @php
                            $totalCameraCost += ($cameraIncidentEquipment->cost * $cameraIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
                @endif
                <p class=" mt-2 text-primary">{{ $totalCameraCost }} ₪</p>
            @endif
        </span>
    </div>
    <div class="mb-sm-0 mb-2">
        <p class="mb-0">Equipment Damaged</p>

        @if($allEnergyIncident)
            @if(count($allEnergyIncident->equipmentDamaged) > 0)
                @foreach($allEnergyIncident->equipmentDamaged as $energyIncidentEquipment)
                    <ul>
                        <li class="text-muted">
                            {{$energyIncidentEquipment->IncidentEquipment->name}} 
                            @if($energyIncidentEquipment->count)
                            <span> ( {{$energyIncidentEquipment->count}}</span> )
                            <span>{{$energyIncidentEquipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @else @if(count($allEnergyIncident->damagedSystemEquipments) > 0)
            
                @foreach($allEnergyIncident->damagedSystemEquipments as $equipment)

                    @php
                        $model = '';

                        if ($equipment->batteryMount) {

                            $model = $equipment->batteryMount->model->model ?? '-';
                        } elseif ($equipment->pv) {

                            $model = $equipment->pv->model->pv_model ?? '-';
                        } elseif ($equipment->inverter) {

                            $model = $equipment->inverter->model->inverter_model ?? '-';
                        } elseif ($equipment->battery) {

                            $model = $equipment->battery->model->battery_model ?? '-';
                        } elseif ($equipment->batteryStatusProcessor) {

                            $model = $equipment->batteryStatusProcessor->model->model ?? '-';
                        } elseif ($equipment->batteryTemperatureSensor) {

                            $model = $equipment->batteryTemperatureSensor->model->BTS_model ?? '-';
                        } elseif ($equipment->chargeController) {

                            $model = $equipment->chargeController->model->charge_controller_model ?? '-';
                        } elseif ($equipment->generator) {

                            $model = $equipment->generator->model->generator_model ?? '-';
                        } elseif ($equipment->loadRelay) {

                            $model = $equipment->loadRelay->model->load_relay_model ?? '-';
                        } elseif ($equipment->mcbChargeController) {

                            $model = $equipment->mcbChargeController->model->model ?? '-';
                        } elseif ($equipment->mcbInverter) {

                            $model = $equipment->mcbInverter->model->inverter_MCB_model ?? '-';
                        } elseif ($equipment->mcbPv) {

                            $model = $equipment->mcbPv->model->model ?? '-';
                        } elseif ($equipment->pvMount) {

                            $model = $equipment->pvMount->model->model ?? '-';
                        } elseif ($equipment->relayDriver) {

                            $model = $equipment->relayDriver->model->model ?? '-';
                        } elseif ($equipment->remoteControlCenter) {

                            $model = $equipment->remoteControlCenter->model->model ?? '-';
                        } elseif ($equipment->windTurbine) {

                            $model = $equipment->windTurbine->model->wind_turbine_model ?? '-';
                        } elseif ($equipment->airConditioner) {

                            $model = $equipment->airConditioner->model->model ?? '-';
                        } elseif ($equipment->monitoring) {

                            $model = $equipment->monitoring->model->monitoring_model ?? '-';
                        }
                        

                    @endphp
                    <ul>
                        <li class="text-muted">
                            {{$model}} 
                            @if($equipment->count)
                            <span> ( {{$equipment->count}}</span> )
                            <span>{{$equipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @endif
            @endif
        @elseif($allWaterIncident)

            @if(count($allWaterIncident->equipmentDamaged) > 0)
                @foreach($allWaterIncident->equipmentDamaged as $waterIncidentEquipment)
                    <ul>
                        <li class="text-muted">
                            {{$waterIncidentEquipment->IncidentEquipment->name}}
                            @if($waterIncidentEquipment->count)
                            <span> ( {{$waterIncidentEquipment->count}}</span> )
                            <span>{{$waterIncidentEquipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @else @if(count($allWaterIncident->damagedSystemEquipments) > 0)
            
                @foreach($allWaterIncident->damagedSystemEquipments as $equipment)

                    @php
                        $model = '';

                        if ($equipment->tank) {

                            $model = $equipment->tank->model->model ?? '-';
                        } elseif ($equipment->tap) {

                            $model = $equipment->tap->model->model ?? '-';
                        } elseif ($equipment->filter) {

                            $model = $equipment->filter->model->model ?? '-';
                        } elseif ($equipment->connector) {

                            $model = $equipment->connector->model->model ?? '-';
                        } elseif ($equipment->pipe) {

                            $model = $equipment->pipe->model->model ?? '-';
                        } elseif ($equipment->pump) {

                            $model = $equipment->pump->model->model ?? '-';
                        } elseif ($equipment->valve) {

                            $model = $equipment->valve->model->model ?? '-';
                        } 
                    @endphp
                    <ul>
                        <li class="text-muted">
                            {{$model}} 
                            @if($equipment->count)
                            <span> ( {{$equipment->count}}</span> )
                            <span>{{$equipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @endif
            @endif

        @elseif($allInternetIncident)

            @if(count($allInternetIncident->equipmentDamaged) > 0)
                @foreach($allInternetIncident->equipmentDamaged as $internetIncidentEquipment)
                    <ul>
                        <li class="text-muted">
                            {{$internetIncidentEquipment->IncidentEquipment->name}}
                            @if($internetIncidentEquipment->count)
                            <span> ( {{$internetIncidentEquipment->count}}</span> )
                            <span>{{$internetIncidentEquipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @else @if(count($allInternetIncident->damagedSystemEquipments) > 0)
            
                @foreach($allInternetIncident->damagedSystemEquipments as $equipment)

                    @php
                        $model = '';

                        if ($equipment->router) {

                            $model = $equipment->router->model->model ?? '-';
                        } elseif ($equipment->switch) {

                            $model = $equipment->switch->model->model ?? '-';
                        } elseif ($equipment->controller) {

                            $model = $equipment->controller->model->model ?? '-';
                        } elseif ($equipment->uisp) {

                            $model = $equipment->uisp->model->model ?? '-';
                        } elseif ($equipment->ptp) {

                            $model = $equipment->ptp->model->model ?? '-';
                        } elseif ($equipment->ap) {

                            $model = $equipment->ap->model->model ?? '-';
                        } elseif ($equipment->aplite) {

                            $model = $equipment->aplite->model->model ?? '-';
                        } elseif ($equipment->connector) {

                            $model = $equipment->connector->model->model ?? '-';
                        } elseif ($equipment->electrician) {

                            $model = $equipment->electrician->model->model ?? '-';
                        } 
                    @endphp
                    <ul>
                        <li class="text-muted">
                            {{$model}} 
                            @if($equipment->count)
                            <span> ( {{$equipment->count}}</span> )
                            <span>{{$equipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @endif
            @endif

        @elseif($allCameraIncident)

            @if(count($allCameraIncident->equipmentDamaged) > 0)
                @foreach($allCameraIncident->equipmentDamaged as $cameraIncidentEquipment)
                    <ul>
                        <li class="text-muted">
                            {{$cameraIncidentEquipment->IncidentEquipment->name}}
                            @if($cameraIncidentEquipment->count)
                            <span> ( {{$cameraIncidentEquipment->count}}</span> )
                            <span>{{$cameraIncidentEquipment->cost}} ₪</span>
                            @endif
                        </li>
                    </ul>
                @endforeach
            @endif
        @endif
    </div>
</div>