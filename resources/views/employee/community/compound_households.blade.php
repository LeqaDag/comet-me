<div id="compoundHouseholds{{$compound->id}}" class="modal fade" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <?php
                    $compoundHouseholds = App\Models\CompoundHousehold::where("compound_id", 
                        $compound->id)
                        ->get();
                ?>
                <h3 class="modal-title fs-5">
                    All Households in {{$compound->english_name}} 
                    @if (count($compoundHouseholds))
                        <span class="text-info">
                           ( {{$compoundHouseholds->count()}} - families)
                        </span>
                    @endif
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($compoundHouseholds))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center"># of Male</th>
                                    <th class="text-center"># of Female</th>
                                    <th class="text-center"># of Adults</th>
                                    <th class="text-center"># of Children</th>
                                    <th class="text-center">Energy System</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($compoundHouseholds as $compoundHousehold)
                                <tr> 
                                    <td class="text-center">
                                        {{ $compoundHousehold->Household->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $compoundHousehold->Household->number_of_male }}
                                    </td>
                                    <td class="text-center">
                                        {{ $compoundHousehold->Household->number_of_female }}
                                    </td>
                                    <td class="text-center">
                                        {{ $compoundHousehold->Household->number_of_adults }}
                                    </td>
                                    <td class="text-center">
                                        {{ $compoundHousehold->Household->number_of_children }}
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $mainUser = null;
                                            $energyUser = App\Models\AllEnergyMeter::where("household_id", 
                                                $compoundHousehold->Household->id)
                                                ->first();
                                            $sharedUser = App\Models\HouseholdMeter::where("household_id", 
                                                $compoundHousehold->Household->id)
                                                ->first();
                                            if($sharedUser) {
                                                
                                                $mainUser = App\Models\AllEnergyMeter::findOrFail($sharedUser->energy_user_id);
                                            }
                                        ?>
                                        @if($energyUser)
                                            {{ $energyUser->EnergySystem->name }}
                                        @else @if($mainUser)
                                            {{ $mainUser->EnergySystem->name }}
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>