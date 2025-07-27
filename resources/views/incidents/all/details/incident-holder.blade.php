@if($allEnergyIncident)
    
    @if($allEnergyIncident->energy_system_id)
    
        {{ $allEnergyIncident->EnergySystem?->name }}
    
    @elseif($allEnergyIncident->all_energy_meter_id)
        @if($allEnergyIncident->AllEnergyMeter->household_id)
            {{ optional($allEnergyIncident->AllEnergyMeter->Household)->english_name }}
        @elseif($allEnergyIncident->AllEnergyMeter->public_structure_id)
            {{ optional($allEnergyIncident->AllEnergyMeter->PublicStructure)->english_name }}
        @endif
    @endif

@elseif($allWaterIncident)

    @if($allWaterIncident->water_system_id)
        {{ $allWaterIncident->WaterSystem->name }}
    
    @elseif($allWaterIncident->all_water_holder_id)
        @if($allWaterIncident->AllWaterHolder->household_id)
            {{ optional($allWaterIncident->AllWaterHolder->Household)->english_name }}
        @elseif($allWaterIncident->AllWaterHolder->public_structure_id)
            {{ optional($allWaterIncident->AllWaterHolder->PublicStructure)->english_name }}
        @endif
    @endif

@elseif($allInternetIncident)

    @if($allInternetIncident->community_id)

        @if($allInternetIncident->InternetSystemCommunity->Compound)
        
            {{$allInternetIncident->InternetSystemCommunity->Compound->english_name}}
        @else 

            {{ optional($allInternetIncident->Community)->english_name }}
        @endif
    
    @elseif($allInternetIncident->internet_user_id)
        @if($allInternetIncident->InternetUser->household_id)
            {{ optional($allInternetIncident->InternetUser->Household)->english_name }}
        @elseif($allInternetIncident->InternetUser->public_structure_id)
            {{ optional($allInternetIncident->InternetUser->PublicStructure)->english_name }}
        @endif
    @endif

@elseif($allCameraIncident)

    @if($allCameraIncident->community_id)
        {{ optional($allCameraIncident->Community)->english_name }}
    @endif

@endif
