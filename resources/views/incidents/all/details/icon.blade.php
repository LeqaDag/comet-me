@if($allEnergyIncident)
    
    @if($allEnergyIncident->energy_system_id)
    
        <i class="bx bx-bulb"></i>
    @elseif($allEnergyIncident->all_energy_meter_id)
        @if($allEnergyIncident->AllEnergyMeter->household_id)
            <i class="bx bx-user"></i>
        @elseif($allEnergyIncident->AllEnergyMeter->public_structure_id)
            <i class="bx bx-home"></i>
        @endif
    @endif

@elseif($allWaterIncident)

    @if($allWaterIncident->water_system_id)

        <i class="bx bx-droplet"></i>
    @elseif($allWaterIncident->all_water_holder_id)
        @if($allWaterIncident->AllWaterHolder->household_id)
            <i class="bx bx-user"></i>
        @elseif($allWaterIncident->AllWaterHolder->public_structure_id)
            <i class="bx bx-home"></i>
        @endif
    @endif

@elseif($allInternetIncident)

    @if($allInternetIncident->community_id)

        <i class="bx bx-wifi"></i>
    @elseif($allInternetIncident->internet_user_id)
        @if($allInternetIncident->InternetUser->household_id)
            <i class="bx bx-user"></i>
        @elseif($allInternetIncident->InternetUser->public_structure_id)
            <i class="bx bx-home"></i>
        @endif
    @endif

@elseif($allCameraIncident)

    <i class="bx bx-camera"></i>
@endif