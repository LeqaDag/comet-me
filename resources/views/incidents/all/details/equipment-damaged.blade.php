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
                @endif
                <p class=" mt-2">{{ $totalEnergyCost }} ₪</p>
            @elseif($allWaterIncident)

                @if(count($allWaterIncident->equipmentDamaged) > 0)
                    @foreach($allWaterIncident->equipmentDamaged as $waterIncidentEquipment)
                        @php
                            $totalWaterCost += ($waterIncidentEquipment->cost * $waterIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
                @endif
                <p class=" mt-2 text-primary">{{ $totalWaterCost }} ₪</p>
            @elseif($allInternetIncident)

                @if(count($allInternetIncident->equipmentDamaged) > 0)
                    @foreach($allInternetIncident->equipmentDamaged as $internetIncidentEquipment)
                        @php
                            $totalInternetCost += ($internetIncidentEquipment->cost * $internetIncidentEquipment->count ?? 0); 
                        @endphp
                    @endforeach
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