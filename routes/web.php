<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('2fa');
  
Route::get('/ticket', [App\Http\Controllers\HomeController::class, 'Ticket'])->name('ticket');
Route::get('/scan', [App\Http\Controllers\HomeController::class, 'Scan'])->name('scan');


Route::get('2fa', [App\Http\Controllers\TwoFAController::class, 'index'])->name('2fa.index');
Route::post('2fa', [App\Http\Controllers\TwoFAController::class, 'store'])->name('2fa.post');
Route::get('2fa/reset', [App\Http\Controllers\TwoFAController::class, 'resend'])->name('2fa.resend');

Route::get('mail-send', [App\Http\Controllers\SendMailController::class, 'index']);

Route::get('/profile-user/{id}', [App\Http\Controllers\Auth\LoginController::class, 'profile'])->name('profile');
Route::resource('user', App\Http\Controllers\UserController::class);
Route::get('/delete-user', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('deleteUser');
Route::get('user/{id}/editpage', [App\Http\Controllers\UserController::class, 'editPage']);

/**
 * Logout Routes 
 */
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::resource('quality-result', App\Http\Controllers\WaterQualityResultController::class);
Route::resource('community', App\Http\Controllers\CommunityController::class);
Route::get('community/destory/{id}', App\Http\Controllers\CommunityController::class.'@destroy');
Route::resource('household', App\Http\Controllers\HouseholdController::class);
Route::get('community/get_by_region/{region_id}', [App\Http\Controllers\CommunityController::class, 'getByRegion']);
Route::get('household/household/{name}', [App\Http\Controllers\HouseholdController::class, 'newProfession']);
Route::get('household/community/{region_id}', [App\Http\Controllers\HouseholdController::class, 'newCommunity']);
Route::get('export', [App\Http\Controllers\HouseholdController::class, 'exportPdf'])->name('export');
Route::get('community/{id}/photo', App\Http\Controllers\CommunityController::class.'@photo');
Route::get('community/{id}/map', App\Http\Controllers\CommunityController::class.'@map');
Route::post('community-export', [App\Http\Controllers\CommunityController::class, 'export'])->name('community.export');
Route::post('household-export', [App\Http\Controllers\HouseholdController::class, 'export'])->name('household.export');
Route::get('progress-household/household/new', [App\Http\Controllers\HouseholdController::class, 'newHousehold']);
Route::get('community/{id}/editpage', [App\Http\Controllers\CommunityController::class, 'editPage']);
Route::get('/delete-community', [App\Http\Controllers\CommunityController::class, 'deleteCommunity'])->name('deleteCommunity');
Route::get('household/community/energy-source/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getCommunityEnergySource']);
Route::get('ac-household/community/energy-source/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getCommunityEnergySource']);

Route::get('/delete-community-compound', [App\Http\Controllers\CommunityController::class, 'deleteCommunityCompound'])->name('deleteCommunityCompound');
 
Route::resource('representative', App\Http\Controllers\CommunityRepresentativeController::class);
Route::get('/delete-representative', [App\Http\Controllers\CommunityRepresentativeController::class, 
    'deleteCommunityRepresentative'])->name('deleteCommunityRepresentative');
Route::get('representative/edit_representative/{id}', [App\Http\Controllers\CommunityRepresentativeController::class, 'updateRepresentative']); 

Route::resource('initial-household', App\Http\Controllers\InitialHouseholdController::class);
Route::get('/initial/ac', [App\Http\Controllers\InitialHouseholdController::class, 'initialToAcSurveyHousehold'])->name('initialToAcSurveyHousehold');
 
Route::resource('ac-household', App\Http\Controllers\AcHouseholdController::class);
Route::get('/ac/served', [App\Http\Controllers\AcHouseholdController::class, 'acToServedSurveyHousehold'])->name('acToServedSurveyHousehold');
Route::get('/ac/sub/household', [App\Http\Controllers\AcHouseholdController::class, 'acSubHousehold'])->name('acSubHousehold');
Route::get('/ac/shared/household', [App\Http\Controllers\AcHouseholdController::class, 'acSubHouseholdSave'])->name('acSubHouseholdSave');
Route::get('/ac/main/household', [App\Http\Controllers\AcHouseholdController::class, 'acMainHousehold'])->name('acMainHousehold');

Route::resource('progress-household', App\Http\Controllers\InProgressHouseholdController::class);

Route::resource('served-household', App\Http\Controllers\ServedHouseholdController::class);

Route::resource('photo', App\Http\Controllers\PhotoController::class);
Route::resource('initial-community', App\Http\Controllers\InitialCommunityController::class);
Route::resource('ac-community', App\Http\Controllers\AcCommunityController::class);
Route::resource('served-community', App\Http\Controllers\ServedCommunityController::class);
Route::resource('water-user', App\Http\Controllers\WaterUserController::class);
Route::get('water-user/get_water_source/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getGridSource']);
Route::get('/delete-water-user', [App\Http\Controllers\WaterUserController::class, 'deleteWaterUser'])->name('deleteWaterUser');
Route::post('water-user-export', [App\Http\Controllers\WaterUserController::class, 'export'])->name('water-user.export');
Route::resource('sub-community', App\Http\Controllers\SubCommunityController::class);
Route::resource('sub-community-household', App\Http\Controllers\SubCommunityHouseholdController::class);
Route::get('/delete-sub-community-household', [App\Http\Controllers\SubCommunityHouseholdController::class, 'deleteSubCommunityHousehold'])->name('deleteSubCommunityHousehold');
Route::post('sub-community-household-export', [App\Http\Controllers\SubCommunityHouseholdController::class, 'export'])->name('sub-community-household.export');
Route::get('sub-community/get_by_community/{community_id}', [App\Http\Controllers\SubCommunityHouseholdController::class, 'getByCommunity']);
Route::resource('community-compound', App\Http\Controllers\CommunityCompoundController::class);
Route::get('community-compound/get_by_community/{community_id}', [App\Http\Controllers\CommunityCompoundController::class, 'getByCommunity']);
Route::get('community-compound/{id}/editpage', [App\Http\Controllers\CommunityCompoundController::class, 'editPage']);
Route::get('/delete-compound-household', [App\Http\Controllers\CommunityCompoundController::class, 'deleteCompoundHousehold'])->name('deleteCompoundHousehold');
Route::post('community-compound-export', [App\Http\Controllers\CommunityCompoundController::class, 'export'])->name('community-compound.export');
Route::get('compound/get_households/get_by_compound/{compound_id}', [App\Http\Controllers\CommunityCompoundController::class, 'getHouseholdByCompound']);

Route::resource('compound', App\Http\Controllers\CompoundController::class);
Route::get('compound/{id}/editpage', [App\Http\Controllers\CompoundController::class, 'editPage']);

Route::get('progress-household/household/get_un_user_by_community/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getNonUserByCommunity']);
Route::get('progress-household/household/get_community_type/{installation_type}', [App\Http\Controllers\HouseholdController::class, 'getCommunityByType']);
Route::get('household/get_by_community/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getByCommunity']);
Route::resource('donor', App\Http\Controllers\DonorController::class);
Route::get('donor/destory/{id}', App\Http\Controllers\DonorController::class.'@destroy');
Route::resource('community-donor', App\Http\Controllers\CommunityDonorController::class);
Route::get('/delete-community-donor', [App\Http\Controllers\CommunityDonorController::class, 'deleteCommunityDonor'])->name('deleteCommunityDonor');
Route::get('community-donor/{id}/editpage', [App\Http\Controllers\CommunityDonorController::class, 'editPage']);
Route::get('/getDonorData/{id}', [App\Http\Controllers\DonorController::class, 'getDonorData'])->name('getDonorData');
Route::get('donor/edit_community_donor/{id}/{donor_id}/{service_id}', [App\Http\Controllers\CommunityDonorController::class, 'updateCommunityDonor']);  
Route::post('donor-export', [App\Http\Controllers\DonorController::class, 'export'])->name('donor.export');
Route::get('donor/{id}/editpage', [App\Http\Controllers\DonorController::class, 'editPage']);

Route::get('region/get_region/{region_id}', [App\Http\Controllers\RegionController::class, 'getByRegion']);
Route::get('region/get_sub_region/{region_id}/{sub_region_id}', [App\Http\Controllers\RegionController::class, 'getBySubRegion']);
Route::get('region/edit_region/{id}', [App\Http\Controllers\RegionController::class, 'updateRegion']);  
Route::get('energy_user/get_by_household/{household_id}', [App\Http\Controllers\EnergyUserController::class, 'getByHousehold']);
Route::get('energy_public/get_by_public/{public_id}', [App\Http\Controllers\WaterPublicStructureController::class, 'getByPublic']);
Route::get('energy_user/get_by_community/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergyUserByCommunity']);
Route::get('energy_public/get_by_community/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getPublicByCommunity']);
Route::get('energy_public/get_by_energy_type/{community_id}/{energy_type_id}', [App\Http\Controllers\EnergyPublicStructureController::class, 'getEnergySystemByCommunity']);
Route::get('energy_user/get_meter/{holder_id}', [App\Http\Controllers\EnergyUserController::class, 'getMeterNumber']);
Route::get('energy_user/get_system_type/{user_id}/{public_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergySystemType']);

Route::resource('sub-region', App\Http\Controllers\SubRegionController::class);
Route::resource('sub-sub-region', App\Http\Controllers\SubSubRegionController::class);
Route::get('/delete-sub-sub', [App\Http\Controllers\SubSubRegionController::class, 'deleteSubSubRegion'])->name('deleteSubSubRegion');
Route::get('sub-region/edit_sub_region/{id}', [App\Http\Controllers\SubRegionController::class, 'updateSubRegion']);
Route::get('/getSubSubRegionData/{id}', [App\Http\Controllers\SubSubRegionController::class, 'getSubSubRegionData'])->name('getSubSubRegionData');
Route::get('/getAllSubSubRegion', [App\Http\Controllers\SubSubRegionController::class, 'getAllSubSubRegion'])->name('getAllSubSubRegion');
Route::get('sub-sub-region/edit_data/{id}', [App\Http\Controllers\SubSubRegionController::class, 'updateSubSubRegion']);

Route::post('/update-sub', [App\Http\Controllers\SubRegionController::class, 'updateSubRegion'])->name('updateSubRegion');
Route::get('/delete-sub', [App\Http\Controllers\SubRegionController::class, 'deleteSubRegion'])->name('deleteSubRegion');
Route::get('/getSubRegionData/{id}', [App\Http\Controllers\SubRegionController::class, 'getSubRegionData'])->name('getSubRegionData');
Route::get('/getRegionData/{id}', [App\Http\Controllers\SubRegionController::class, 'getRegionData'])->name('getRegionData');
Route::get('/getAllSubRegion', [App\Http\Controllers\SubRegionController::class, 'getAllSubRegion'])->name('getAllSubRegion');
Route::get('/delete-region', [App\Http\Controllers\RegionController::class, 'deleteRegion'])->name('deleteRegion');

Route::resource('energy-user', App\Http\Controllers\EnergyUserController::class);
Route::get('ac-household/energy-user/get_by_community/{community_id}/{misc}', [App\Http\Controllers\EnergyUserController::class, 'getHouseholdByCommunity']);
Route::get('progress-household/energy-user/get_by_energy_type/{energy_type_id}/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergySystemByType']);
Route::get('ac-household/energy-user/shared_household/{community_id}/{user_id}', [App\Http\Controllers\EnergyUserController::class, 'getSharedHousehold']);
Route::get('ac-household/energy-user/get_misc/{misc}', [App\Http\Controllers\EnergyUserController::class, 'getMiscCommunity']);
Route::post('energy-user-export', [App\Http\Controllers\EnergyUserController::class, 'export'])->name('energy-user.export');
Route::get('progress-household/household/get_by_community/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getByCommunity']);
Route::post('all-meter-export', [App\Http\Controllers\AllEnergyController::class, 'export'])->name('energy-meter.export');
Route::post('all-meter-import', [App\Http\Controllers\AllEnergyController::class, 'import'])->name('energy-meter.import');
Route::post('energy-safety-import', [App\Http\Controllers\EnergySafetyController::class, 'import'])->name('energy-safety.import');

Route::resource('energy-system', App\Http\Controllers\EnergySystemController::class);
Route::get('energy-system/{id}/editpage', [App\Http\Controllers\EnergySystemController::class, 'editPage']);
Route::resource('energy-component', App\Http\Controllers\EnergyComponentController::class);
Route::get('energy-system/{id}/showPage', [App\Http\Controllers\EnergySystemController::class, 'showPage']);
Route::get('/delete-energy-system', [App\Http\Controllers\EnergySystemController::class, 'deleteEnergySystem'])->name('deleteEnergySystem');
Route::post('energy-system-export', [App\Http\Controllers\EnergySystemController::class, 'export'])->name('energy-system.export');

Route::get('/delete-energy-system-battery', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemBattery'])->name('deleteEnergySystemBattery');
Route::get('/delete-energy-system-battery-mount', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemBatteryMount'])->name('deleteEnergySystemBatteryMount');
Route::get('/delete-energy-system-pv-mount', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemPvMount'])->name('deleteEnergySystemPvMount');
Route::get('/delete-energy-system-pv', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemPv'])->name('deleteEnergySystemPv');
Route::get('/delete-energy-system-controller', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemController'])->name('deleteEnergySystemController');
Route::get('/delete-energy-system-mcb-pv', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemMcbPv'])->name('deleteEnergySystemMcbPv');
Route::get('/delete-energy-system-bsp', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemBsp'])->name('deleteEnergySystemBsp');
Route::get('/delete-energy-system-monitoring', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemMonitoring'])->name('deleteEnergySystemMonitoring');
Route::get('/delete-energy-system-load-relay', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemLoadRelay'])->name('deleteEnergySystemLoadRelay');
Route::get('/delete-energy-system-inventer', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemInverter'])->name('deleteEnergySystemInverter');
Route::get('/delete-energy-system-generator', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemGenerator'])->name('deleteEnergySystemGenerator');
Route::get('/delete-energy-system-relay-driver', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemRelayDriver'])->name('deleteEnergySystemRelayDriver');
Route::get('/delete-energy-system-turbine', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemTurbine'])->name('deleteEnergySystemTurbine');
Route::get('/delete-energy-system-rcc', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemRcc'])->name('deleteEnergySystemRcc');
Route::get('/delete-energy-system-mcb-controller', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemMcbController'])->name('deleteEnergySystemMcbController');
Route::get('/delete-energy-system-mcb-inventer', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemMcbInverter'])->name('deleteEnergySystemMcbInverter');
Route::get('/delete-energy-system-air', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemAirConditioner'])->name('deleteEnergySystemAirConditioner');
Route::get('/delete-energy-system-bts', [App\Http\Controllers\EnergyComponentController::class, 'deleteEnergySystemBts'])->name('deleteEnergySystemBts');

Route::resource('water-system', App\Http\Controllers\WaterSystemController::class);
Route::get('water_user/get_by_community/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getWaterUserByCommunity']);
Route::get('water_public/get_by_community/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getPublicByCommunity']);
Route::get('water-system/{id}/editpage', [App\Http\Controllers\WaterSystemController::class, 'editPage']);
Route::get('/delete-shared-public', [App\Http\Controllers\WaterPublicStructureController::class, 'deleteSharedPublic'])->name('deleteSharedPublic');
Route::get('/delete-waterSystem', [App\Http\Controllers\WaterSystemController::class, 'deleteWaterSystem'])->name('deleteWaterSystem');
Route::post('water-system/export', [App\Http\Controllers\WaterSystemController::class, 'exportWaterHolders'])->name('water-system.export');
Route::get('water-system/getHolders/{id}', [App\Http\Controllers\WaterSystemController::class, 'getWaterHolders']);

Route::get('/details/incident', [App\Http\Controllers\HomeController::class, 'incidentDetails'])->name('incidentDetails');
Route::get('/water/chart/', [App\Http\Controllers\WaterUserController::class, 'chartWater'])->name('chartWater');
Route::get('/details/chart', [App\Http\Controllers\WaterUserController::class, 'waterChartDetails'])->name('waterChartDetails');
Route::get('/delete-household', [App\Http\Controllers\HouseholdController::class, 'deleteHousehold'])->name('deleteHousehold');
Route::get('household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);
Route::get('public/get_by_community/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getAllPublic']);
Route::get('requested-household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);
Route::get('initial-household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);
Route::get('progress-household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);
Route::get('served-household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);

Route::resource('all-meter', App\Http\Controllers\AllEnergyController::class);
Route::get('/allMeter/{id}', [App\Http\Controllers\AllEnergyController::class, 'getEnergyUserData'])->name('getEnergyUserData');
Route::get('/allMeter/info/{id}', [App\Http\Controllers\AllEnergyController::class, 'updateEnergyUserData'])->name('updateEnergyUserData');
Route::get('/all-meter/{id}/donor', [App\Http\Controllers\AllEnergyController::class, 'getEnergyUserDonors'])->name('getEnergyUserDonors');
Route::get('/allMeter/donor/{id}', [App\Http\Controllers\AllEnergyController::class, 'editDonor'])->name('editDonor');
Route::get('/delete-energyUser', [App\Http\Controllers\AllEnergyController::class, 'deleteEnergyUser'])->name('deleteEnergyUser');
Route::get('/delete-energyDonor', [App\Http\Controllers\AllEnergyController::class, 'deleteEnergyDonor'])->name('deleteEnergyDonor');

Route::resource('energy-public', App\Http\Controllers\EnergyPublicStructureController::class);
Route::resource('comet-meter', App\Http\Controllers\EnergyCometMeterController::class);
Route::get('energy-public/get_by_community/{community_id}/{comet_meter}', [App\Http\Controllers\EnergyPublicStructureController::class, 'getByCommunity']);
Route::get('/delete-public', [App\Http\Controllers\EnergyPublicStructureController::class, 'deleteEnergyPublic'])->name('deleteEnergyPublic');
Route::get('/delete-comet-meter', [App\Http\Controllers\EnergyCometMeterController::class, 'deleteCometMeter'])->name('deleteCometMeter');
Route::get('energy_public/{id}/editpage', [App\Http\Controllers\EnergyPublicStructureController::class, 'editPage']);
Route::get('/delete-publicDonor', [App\Http\Controllers\EnergyPublicStructureController::class, 'deleteEnergyPublicDonor'])->name('deleteEnergyPublicDonor');
Route::get('comet-meter/{id}/editpage', [App\Http\Controllers\EnergyCometMeterController::class, 'editPage']);
Route::post('energy-public-export', [App\Http\Controllers\EnergyPublicStructureController::class, 'export'])->name('energy-public.export');
Route::post('comet-meter-export', [App\Http\Controllers\EnergyCometMeterController::class, 'export'])->name('comet-meter.export');

Route::resource('internet-user', App\Http\Controllers\InternetUserController::class);
Route::get('/details/fbs/incident', [App\Http\Controllers\EnergySystemController::class, 'incidentFbsDetails'])->name('incidentFbsDetails');
Route::post('internet-user-export', [App\Http\Controllers\InternetUserController::class, 'export'])->name('internet-user.export');
Route::get('internet-user/{id}/editpage', [App\Http\Controllers\InternetUserController::class, 'editPage']);
Route::get('/delete-internetDonor', [App\Http\Controllers\InternetUserController::class, 'deleteInternetDonor'])->name('deleteInternetDonor');
Route::get('/delete-internet-user', [App\Http\Controllers\InternetUserController::class, 'deleteInternetHolder'])->name('deleteInternetHolder');
Route::get('internet-user/get_by_household/{household_public_id}/{is_household}', [App\Http\Controllers\InternetUserController::class, 'getDetailsByHouseholdPublic']);
Route::get('internet-user/get_by_community/{community_id}/{is_household}', [App\Http\Controllers\InternetUserController::class, 'getHousholdsPublicByCommunity']);

Route::resource('household-meter', App\Http\Controllers\HouseholdMeterController::class);
Route::get('household-meter/get_households/{id}', [App\Http\Controllers\HouseholdMeterController::class, 'getHouseholds'])->name('getHouseholds');
Route::get('/delete-household-meter', [App\Http\Controllers\HouseholdMeterController::class, 'deleteHouseholdMeter'])->name('deleteHouseholdMeter');
Route::get('household-meter/get_users/{community_id}', [App\Http\Controllers\HouseholdMeterController::class, 'getUsers'])->name('getUsers');
Route::post('household-meter-export', [App\Http\Controllers\HouseholdMeterController::class, 'export'])->name('household-meter.export');
Route::get('household-meter/get_publics/{id}', [App\Http\Controllers\HouseholdMeterController::class, 'getPublicStructures'])->name('getPublicStructures');

Route::resource('all-water', App\Http\Controllers\AllWaterController::class);
Route::resource('shared-h2o', App\Http\Controllers\SharedWaterController::class);
Route::resource('water-public', App\Http\Controllers\WaterPublicStructureController::class); 
Route::resource('shared-grid', App\Http\Controllers\WaterSharedGridController::class); 
Route::get('all-water/{id}/editpage', [App\Http\Controllers\AllWaterController::class, 'editPage']);
Route::get('shared-h2o/get_by_community/{community_id}', [App\Http\Controllers\SharedWaterController::class, 'getH2oUsersByCommunity']);
Route::get('/delete-shared-h2o', [App\Http\Controllers\SharedWaterController::class, 'deleteSharedWaterUser'])->name('deleteSharedWaterUser');
Route::get('water-public/get_by_community/{community_id}', [App\Http\Controllers\WaterPublicStructureController::class, 'getH2oPublicByCommunity']);
Route::get('allMeter/{id}/editpage', [App\Http\Controllers\AllEnergyController::class, 'editPage']);
Route::get('/delete-waterDonor', [App\Http\Controllers\AllWaterController::class, 'deleteWaterDonor'])->name('deleteWaterDonor');
Route::get('shared-grid/get_by_community/{community_id}', [App\Http\Controllers\WaterSharedGridController::class, 'getGridUsersByCommunity']);
Route::get('/delete-shared-grid', [App\Http\Controllers\WaterSharedGridController::class, 'deleteSharedGridUser'])->name('deleteSharedGridUser');

Route::resource('water-maintenance', App\Http\Controllers\H2oMaintenanceCallController::class);
Route::post('water-maintenance-export', [App\Http\Controllers\H2oMaintenanceCallController::class, 'export'])->name('water-maintenance.export');
Route::get('/delete-h2o-action', [App\Http\Controllers\H2oMaintenanceCallController::class, 'deleteH2oAction'])->name('deleteH2oAction');
Route::get('/delete-h2o-user', [App\Http\Controllers\H2oMaintenanceCallController::class, 'deletePerformedUsers'])->name('deletePerformedUsers');
 
Route::resource('internet-system', App\Http\Controllers\InternetSystemController::class);
Route::get('internet-system/{id}/showPage', [App\Http\Controllers\InternetSystemController::class, 'showPage']);
Route::get('/delete-internet-system', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystem'])->name('deleteInternetSystem');
Route::get('internet-system/{id}/editpage', [App\Http\Controllers\InternetSystemController::class, 'editPage']);
Route::get('/delete-internet-system-type', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemType'])->name('deleteInternetSystemType');

Route::get('/delete-internet-system-router', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemRouter'])->name('deleteInternetSystemRouter');
Route::get('/delete-internet-system-switch', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemSwitch'])->name('deleteInternetSystemSwitch');
Route::get('/delete-internet-system-controller', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemController'])->name('deleteInternetSystemController');
Route::get('/delete-internet-system-ap', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemAp'])->name('deleteInternetSystemAp');
Route::get('/delete-internet-system-aplite', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemApLite'])->name('deleteInternetSystemApLite');
Route::get('/delete-internet-system-uisp', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemUisp'])->name('deleteInternetSystemUisp');
Route::get('/delete-internet-system-ptp', [App\Http\Controllers\InternetSystemController::class, 'deleteInternetSystemPtp'])->name('deleteInternetSystemPtp');

Route::resource('internet-component', App\Http\Controllers\InternetComponentController::class);

Route::get('/', [App\Http\Controllers\HomeController::class, 'showMainPage']);
Route::get('downloadPdf', [App\Http\Controllers\HomeController::class, 'downloadPdf']);

Route::get('allMeter/donor/{id}/editDonor', [App\Http\Controllers\AllEnergyController::class, 'editDonor']);
Route::get('/delete-maintenance', [App\Http\Controllers\H2oMaintenanceCallController::class, 'deleteMaintenanceWater'])->name('deleteMaintenanceWater');
Route::get('/details/h2o/incident', [App\Http\Controllers\WaterSystemController::class, 'incidentH2oDetails'])->name('incidentH2oDetails');

Route::get('household-fbs', [App\Http\Controllers\ExportController::class, 'index']);
Route::post('household-import', [App\Http\Controllers\ExportController::class, 'import'])->name('household.import');

Route::resource('chart', App\Http\Controllers\ChartController::class); 
Route::get('chart/service/{service_id}/{region_id}', [App\Http\Controllers\ChartController::class, 'getByService']);
Route::resource('energy-maintenance', App\Http\Controllers\EnergyMaintenanceCallController::class);
Route::get('/delete-energy-maintenance', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'deleteMaintenanceEnergy'])->name('deleteMaintenanceEnergy');
Route::post('energy-maintenance-export', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'export'])->name('energy-maintenance.export');
Route::get('energy-maintenance/get_holder/{flag}/{community_id}', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'getAgent']); 
Route::get('/delete-energy-performed', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'deletePerformedEnergyUsers'])->name('deletePerformedEnergyUsers');
Route::get('energy-maintenance/get_energy/{community_id}', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'getEnergySystem'])->name('getEnergySystem');
Route::post('energy-maintenance-import', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'import'])->name('energy-maintenance.import');
Route::get('/delete-maintenance-action', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'deleteEnergyAction'])->name('deleteEnergyAction');
Route::get('energy-maintenance/get_actions/{issue_id}', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'getActionsByIssue']); 

Route::resource('energy-safety', App\Http\Controllers\EnergySafetyController::class);
Route::get('/delete-energy-safety', [App\Http\Controllers\EnergySafetyController::class, 'deleteEnergySafety'])->name('deleteEnergySafety');
Route::get('energy-safety/{id}/editpage', [App\Http\Controllers\EnergySafetyController::class, 'editPage']);
Route::post('energy-safety-export', [App\Http\Controllers\EnergySafetyController::class, 'export'])->name('energy-safety.export');
Route::get('energy_safety/info/{holder_id}/{publicUser}', [App\Http\Controllers\EnergySafetyController::class, 'getInfo']);

Route::resource('new-energy-maintenance', App\Http\Controllers\NewEnergyMaintenanceCallController::class);
Route::get('/delete-new-energy-maintenance', [App\Http\Controllers\NewEnergyMaintenanceCallController::class, 'deleteNewMaintenanceEnergy'])->name('deleteNewMaintenanceEnergy');
Route::post('new-energy-maintenance-export', [App\Http\Controllers\NewEnergyMaintenanceCallController::class, 'export'])->name('new-energy-maintenance.export');

Route::resource('refrigerator-user', App\Http\Controllers\RefrigeratorHolderController::class);
Route::get('/delete-refrigerator', [App\Http\Controllers\RefrigeratorHolderController::class, 'deleteRefrigeratorHolder'])->name('deleteRefrigeratorHolder');
Route::post('refrigerator-export', [App\Http\Controllers\RefrigeratorHolderController::class, 'export'])->name('refrigerator.export');
Route::post('refrigerator-import', [App\Http\Controllers\RefrigeratorHolderController::class, 'import'])->name('refrigerator.import');
Route::get('refrigerator-user/household/{id}', [App\Http\Controllers\RefrigeratorHolderController::class, 'getPhoneNumber']);

Route::resource('refrigerator-maintenance', App\Http\Controllers\RefrigeratorMaintenanceCallController::class);
Route::get('/delete-refrigerator-maintenance', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'deleteRefrigerator'])->name('deleteRefrigerator');
Route::post('refrigerator-maintenance-export', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'export'])->name('refrigerator-maintenance.export');
Route::get('refrigerator-user/get_by_community/{community_id}/{flag}', [App\Http\Controllers\RefrigeratorHolderController::class, 'getHouseholdByCommunity']);
Route::get('refrigerator-public/get_by_community/{community_id}', [App\Http\Controllers\RefrigeratorHolderController::class, 'getPublicByCommunity']);
Route::get('/delete-refrigerator-action', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'deleteRefrigeratorAction'])->name('deleteRefrigeratorAction');
Route::get('/delete-refrigerator-performed', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'deletePerformedRefrigeratorUsers'])->name('deletePerformedRefrigeratorUsers');

Route::resource('mg-incident', App\Http\Controllers\MgIncidentController::class);
Route::get('/delete-mg-incident', [App\Http\Controllers\MgIncidentController::class, 'deleteMgIncident'])->name('deleteMgIncident');
Route::post('mg-incident-export', [App\Http\Controllers\MgIncidentController::class, 'export'])->name('mg-incident.export');
Route::get('mg-incident/get_system_by_community/{community_id}', [App\Http\Controllers\MgIncidentController::class, 'getSystemByCommunity']);
Route::get('mg-incident/get_by_type/{incident_type_id}', [App\Http\Controllers\MgIncidentController::class, 'getStatusByIncidentType']);
Route::get('/delete-mg-equipment', [App\Http\Controllers\MgIncidentController::class, 'deleteMgIncidentEquipment'])->name('deleteMgIncidentEquipment');
Route::get('/delete-mg-household', [App\Http\Controllers\MgIncidentController::class, 'deletemgAffectedHousehold'])->name('deletemgAffectedHousehold');
Route::get('mg-incident/get_household_by_community/{community_id}', [App\Http\Controllers\MgIncidentController::class, 'getHouseholdByCommunity']);
Route::get('/delete-mg-photo', [App\Http\Controllers\MgIncidentController::class, 'deleteMgIncidentPhoto'])->name('deleteMgIncidentPhoto');

Route::resource('fbs-incident', App\Http\Controllers\FbsIncidentController::class);
Route::get('/delete-fbs-incident', [App\Http\Controllers\FbsIncidentController::class, 'deleteFbsIncident'])->name('deleteFbsIncident');
Route::post('fbs-incident-export', [App\Http\Controllers\FbsIncidentController::class, 'export'])->name('fbs-incident.export');
Route::get('/delete-fbs-equipment/{id}', [App\Http\Controllers\FbsIncidentController::class, 'deleteIncidentEquipment'])->name('deleteIncidentEquipment');
Route::get('/delete-fbs-status', [App\Http\Controllers\FbsIncidentController::class, 'deleteIncidentStatus'])->name('deleteIncidentStatus');
Route::get('/delete-fbs-photo', [App\Http\Controllers\FbsIncidentController::class, 'deleteIncidentPhoto'])->name('deleteIncidentPhoto');
Route::get('fbs-incident/get_by_type/{incident_type_id}', [App\Http\Controllers\FbsIncidentController::class, 'getStatusByIncidentType']);

Route::resource('water-incident', App\Http\Controllers\WaterIncidentController::class);
Route::get('/delete-water-incident', [App\Http\Controllers\WaterIncidentController::class, 'deleteWaterIncident'])->name('deleteWaterIncident');
Route::post('water-incident-export', [App\Http\Controllers\WaterIncidentController::class, 'export'])->name('water-incident.export');
Route::get('/delete-water-equipment', [App\Http\Controllers\WaterIncidentController::class, 'deleteWaterIncidentEquipment'])->name('deleteWaterIncidentEquipment');
Route::get('/delete-water-photo', [App\Http\Controllers\WaterIncidentController::class, 'deleteWaterIncidentPhoto'])->name('deleteWaterIncidentPhoto');
Route::get('/delete-water-status', [App\Http\Controllers\WaterIncidentController::class, 'deleteWaterIncidentStatus'])->name('deleteWaterIncidentStatus');

// internet incidents
Route::resource('incident-network', App\Http\Controllers\InternetNetworkIncidentController::class);
Route::resource('incident-internet-user', App\Http\Controllers\InternetUserIncidentController::class);
Route::post('incident-internet-user-export', [App\Http\Controllers\InternetUserIncidentController::class, 'export'])->name('incident-internet-user.export');
Route::get('incident-internet-user/get_by_community/{community_id}/{flag}', [App\Http\Controllers\InternetUserIncidentController::class, 'getInternetUsersByCommunity']);
Route::get('/delete-internet-user-equipment', [App\Http\Controllers\InternetUserIncidentController::class, 'deleteIncidentEquipment'])->name('deleteIncidentEquipment');
Route::get('/delete-incident-internet-user', [App\Http\Controllers\InternetUserIncidentController::class, 'deleteInternetUserIncident'])->name('deleteInternetUserIncident');
Route::get('/delete-incident-network', [App\Http\Controllers\InternetNetworkIncidentController::class, 'deleteInternetNetworkIncident'])->name('deleteInternetNetworkIncident');
Route::post('incident-network-export', [App\Http\Controllers\InternetNetworkIncidentController::class, 'export'])->name('incident-network.export');
Route::get('/delete-network-area', [App\Http\Controllers\InternetNetworkIncidentController::class, 'deleteAreaAffected'])->name('deleteAreaAffected');
Route::get('/delete-network-household', [App\Http\Controllers\InternetNetworkIncidentController::class, 'deleteAffectedHousehold'])->name('deleteAffectedHousehold');
Route::get('/delete-network-equipment', [App\Http\Controllers\InternetNetworkIncidentController::class, 'deleteNetworkEquipment'])->name('deleteNetworkEquipment');
Route::get('/delete-network-photo', [App\Http\Controllers\InternetNetworkIncidentController::class, 'deleteNetworkPhoto'])->name('deleteNetworkPhoto');
Route::get('/delete-internet-incident-photo', [App\Http\Controllers\InternetUserIncidentController::class, 'deleteInternetUserPhoto'])->name('deleteInternetUserPhoto');

// Camera incidents
Route::resource('incident-camera', App\Http\Controllers\CameraIncidentController::class);
Route::post('incident-camera-export', [App\Http\Controllers\CameraIncidentController::class, 'export'])->name('camera-incident.export');
Route::get('/delete-incident-camera', [App\Http\Controllers\CameraIncidentController::class, 'deleteCameraIncident'])->name('deleteCameraIncident');
Route::get('/delete-camera-photo', [App\Http\Controllers\CameraIncidentController::class, 'deleteCameraIncidentPhoto'])->name('deleteCameraIncidentPhoto');
Route::get('/delete-camera-equipment', [App\Http\Controllers\CameraIncidentController::class, 'deleteCameraIncidentEquipment'])->name('deleteCameraIncidentEquipment');


Route::post('quality-result-export', [App\Http\Controllers\WaterQualityResultController::class, 'export'])->name('quality-result.export');
Route::get('water_holder/get_by_community/{community_id}/{flag}', [App\Http\Controllers\WaterQualityResultController::class, 'getWaterHolderByCommunity']);
Route::get('/delete-quality-result', [App\Http\Controllers\WaterQualityResultController::class, 'deleteQualityResult'])->name('deleteQualityResult');
Route::get('quality-result/{id}/editpage', [App\Http\Controllers\WaterQualityResultController::class, 'editPage']);
Route::get('quality-result/summary/{year}', [App\Http\Controllers\WaterQualityResultController::class, 'summary']);
Route::post('quality-result-import', [App\Http\Controllers\WaterQualityResultController::class, 'import'])->name('quality-result.import');

Route::resource('water-summary',  App\Http\Controllers\WaterQualitySummaryController::class);
Route::get('quality-result/cfu/max/{id}/{year}', [App\Http\Controllers\WaterQualitySummaryController::class, 'cfuMax']);
Route::post('water-summary-export', [App\Http\Controllers\WaterQualitySummaryController::class, 'export'])->name('water-summary.export');
Route::get('/water-summary-result/chart', [App\Http\Controllers\WaterQualitySummaryController::class, 'chartWaterResult'])->name('chartWaterResult');

Route::resource('region', App\Http\Controllers\RegionController::class); 

Route::resource('setting', App\Http\Controllers\SettingController::class); 
Route::get('setting/edit_setting/{id}', [App\Http\Controllers\SettingController::class, 'updateSetting']);

Route::resource('all-active', App\Http\Controllers\AllActiveUserController::class); 
Route::post('all-active-export', [App\Http\Controllers\AllActiveUserController::class, 'export'])->name('all-active.export');

Route::resource('action-item', App\Http\Controllers\ActionItemController::class); 
Route::get('action-item/household/missing', [App\Http\Controllers\ActionItemController::class, 'householdMissingDetails']);
Route::get('missing/donors/{community_id}', [App\Http\Controllers\ActionItemController::class, 'householdMissingDonors']);
Route::get('action-item/ac-household/export', [App\Http\Controllers\ActionItemController::class, 'householdAcExport']);
Route::get('action-item/in-progress-household/export', [App\Http\Controllers\ActionItemController::class, 'householdInProgressExport']);
Route::get('action-item/new-household/missing', [App\Http\Controllers\ActionItemController::class, 'newHouseholdMissingDetails']);

Route::resource('action-item-user', App\Http\Controllers\ActionItemUserController::class); 
Route::get('/delete-action-item-user', [App\Http\Controllers\ActionItemUserController::class, 'deleteUserActionItem'])->name('deleteUserActionItem');

Route::resource('public-structure', App\Http\Controllers\PublicStructureController::class);
Route::post('public-structure-export', [App\Http\Controllers\PublicStructureController::class, 'export'])->name('public-structure.export');
Route::get('/delete-public-structure', [App\Http\Controllers\PublicStructureController::class, 'deletePublicStructure'])->name('deletePublicStructure');
Route::get('public/{id}/editpage', [App\Http\Controllers\PublicStructureController::class, 'editPage']);
Route::get('/delete-school-public-structure', [App\Http\Controllers\PublicStructureController::class, 'deleteschoolCommunity'])->name('deleteschoolCommunity');

Route::resource('energy-request', App\Http\Controllers\EnergyRequestSystemController::class);
Route::post('energy-request-export', [App\Http\Controllers\EnergyRequestSystemController::class, 'export'])->name('energy-request.export');
Route::get('energy-request/energy-request/get_by_community/{community_id}', [App\Http\Controllers\EnergyRequestSystemController::class, 'getRequestedByCommunity']);
Route::post('energy-request-household-export', [App\Http\Controllers\EnergyRequestSystemController::class, 'exportRequested'])->name('energy-request-household.export');
Route::get('/delete-energy-request', [App\Http\Controllers\EnergyRequestSystemController::class, 'deleteEnergyRequest'])->name('deleteEnergyRequest');
Route::get('/move-energy-request', [App\Http\Controllers\EnergyRequestSystemController::class, 'moveEnergyRequest'])->name('moveEnergyRequest');
Route::get('/postpone-energy-request', [App\Http\Controllers\EnergyRequestSystemController::class, 'postponedEnergyRequest'])->name('postponedEnergyRequest');

Route::resource('requested-household', App\Http\Controllers\RequestedHouseholdController::class); 

Route::resource('displaced-household', App\Http\Controllers\DisplacedHouseholdController::class);
Route::get('/delete-displaced-household', [App\Http\Controllers\DisplacedHouseholdController::class, 'deleteDisplacedHousehold'])->name('deleteDisplacedHousehold');
Route::get('displaced-household/get_household_by_community/{community_id}', [App\Http\Controllers\DisplacedHouseholdController::class, 'getHouseholdByCommunity']);
Route::get('displaced-household/get_system_by_community/{community_id}', [App\Http\Controllers\DisplacedHouseholdController::class, 'getSystemsByCommunity']);
Route::post('displaced-household-export', [App\Http\Controllers\DisplacedHouseholdController::class, 'export'])->name('displaced-household.export');
Route::get('displaced-household/{id}/editpage', [App\Http\Controllers\DisplacedHouseholdController::class, 'editPage']);

Route::get('filter_map', [App\Http\Controllers\HomeController::class, 'CommunityMapFilter']);

Route::resource('camera', App\Http\Controllers\CameraCommunityController::class);
Route::get('/delete-community-camera', [App\Http\Controllers\CameraCommunityController::class, 
    'deleteCameraCommunity'])->name('deleteCameraCommunity');
Route::get('/delete-camera-type', [App\Http\Controllers\CameraCommunityController::class, 'deleteCommunityCamera'])->name('deleteCommunityCamera');
Route::get('/delete-nvr-camera-type', [App\Http\Controllers\CameraCommunityController::class, 'deleteCommunityNvrCamera'])->name('deleteCommunityNvrCamera');
Route::get('/delete-community-camera-photo', [App\Http\Controllers\CameraCommunityController::class, 'deleteCommunityCameraPhoto'])->name('deleteCommunityCameraPhoto');
Route::post('camera-export', [App\Http\Controllers\CameraCommunityController::class, 'export'])->name('camera.export');
Route::post('camera/update_ip', [App\Http\Controllers\CameraCommunityController::class, 'updateIpAddress']);
Route::get('/delete-cameraDonor', [App\Http\Controllers\CameraCommunityController::class, 'deleteCameraDonor'])->name('deleteCameraDonor');

Route::resource('displaced-community', App\Http\Controllers\DisplacedCommunityController::class);

Route::resource('camera-component', App\Http\Controllers\CameraComponentController::class); 
Route::get('camera-component/{id}/editpage', [App\Http\Controllers\CameraComponentController::class, 'editPage']);
Route::get('/delete-camera', [App\Http\Controllers\CameraComponentController::class, 'deleteCamera'])->name('deleteCamera');
Route::resource('nvr-component', App\Http\Controllers\NvrComponentController::class);
Route::get('nvr-component/{id}/editpage', [App\Http\Controllers\NvrComponentController::class, 'editPage']);
Route::get('/delete-nvr', [App\Http\Controllers\NvrComponentController::class, 'deleteNvr'])->name('deleteNvr');

Route::resource('hold-household', App\Http\Controllers\OnHoldHouseholdController::class);
Route::get('/delete-hold-household', [App\Http\Controllers\OnHoldHouseholdController::class, 'deleteOnHoldHousehold'])->name('deleteOnHoldHousehold');

Route::resource('work-plan', App\Http\Controllers\WorkPlanController::class);
Route::get('/delete-work-plan', [App\Http\Controllers\WorkPlanController::class, 'deleteWorkPlan'])->name('deleteWorkPlan');
Route::get('work-plan/other/{id}', [App\Http\Controllers\WorkPlanController::class, 'getOtherUser']);
Route::get('/delete-other-user', [App\Http\Controllers\WorkPlanController::class, 'deleteOtherUser'])->name('deleteOtherUser');
Route::get('work-plan/other/user', [App\Http\Controllers\WorkPlanController::class, 'getOtherUsers']);

Route::get('/delete-other-user-admin', [App\Http\Controllers\WorkPlanController::class, 'deleteOtherUserFromAdmin'])->name('deleteOtherUserFromAdmin');

Route::resource('internet-maintenance', App\Http\Controllers\InternetMaintenanceCallController::class);
Route::post('internet-maintenance-export', [App\Http\Controllers\InternetMaintenanceCallController::class, 'export'])->name('internet-maintenance.export');
Route::resource('internet-issue', App\Http\Controllers\InternetIssueController::class);
Route::resource('internet-action', App\Http\Controllers\InternetActionController::class);
Route::get('/delete-internet-issue', [App\Http\Controllers\InternetIssueController::class, 'deleteInternetMainIssue'])->name('deleteInternetMainIssue');
Route::post('internet-issue-export', [App\Http\Controllers\InternetIssueController::class, 'export'])->name('internet-issue.export');
Route::post('internet-action-export', [App\Http\Controllers\InternetActionController::class, 'export'])->name('internet-action.export');
Route::get('/delete-internet-action', [App\Http\Controllers\InternetActionController::class, 'deleteInternetMainAction'])->name('deleteInternetMainAction');
Route::get('/internet-action/get/{id}', [App\Http\Controllers\InternetActionController::class, 'getInternetAction']);
Route::get('/internet-issue/get/{id}', [App\Http\Controllers\InternetIssueController::class, 'getInternetIssue']);

Route::get('internet_user/get_by_community/{community_id}', [App\Http\Controllers\InternetMaintenanceCallController::class, 'getHouseholdByCommunity']);
Route::get('internet_public/get_by_community/{community_id}', [App\Http\Controllers\InternetMaintenanceCallController::class, 'getPublicByCommunity']);
Route::get('internet-maintenance/get_actions/{issue_id}', [App\Http\Controllers\InternetMaintenanceCallController::class, 'getActions']);
Route::get('/delete-internet-maintenance', [App\Http\Controllers\InternetMaintenanceCallController::class, 'deleteInternetMaintenance'])->name('deleteInternetMaintenance');
Route::get('/delete-internet-maintenance-user', [App\Http\Controllers\InternetMaintenanceCallController::class, 'deletePerformedInternetUser'])->name('deletePerformedInternetUser');
Route::get('/delete-internet-maintenance-action', [App\Http\Controllers\InternetMaintenanceCallController::class, 'deleteInternetAction'])->name('deleteInternetAction');

Route::resource('energy-issue', App\Http\Controllers\EnergyIssueController::class);
Route::post('energy-issue-export', [App\Http\Controllers\EnergyIssueController::class, 'export'])->name('energy-issue.export');
Route::get('/delete-energy-issue', [App\Http\Controllers\EnergyIssueController::class, 'deleteEnergyIssue'])->name('deleteEnergyIssue');
Route::get('/energy-issue/get/{id}', [App\Http\Controllers\EnergyIssueController::class, 'getEnergyIssue']);
Route::get('/energy-issue/get_by_action_category/{id}', [App\Http\Controllers\EnergyIssueController::class, 'getEnergyActionBasedOnCategory']);

Route::resource('energy-action', App\Http\Controllers\EnergyActionController::class);
Route::post('energy-action-export', [App\Http\Controllers\EnergyActionController::class, 'export'])->name('energy-action.export');
Route::get('/delete-energy-action', [App\Http\Controllers\EnergyActionController::class, 'deleteEnergyMainAction'])->name('deleteEnergyMainAction');
Route::get('/energy-action/get/{id}', [App\Http\Controllers\EnergyActionController::class, 'getEnergyIssue']);

Route::resource('energy-cost', App\Http\Controllers\EnergyCostController::class); 
Route::get('energy-cost/{id}/editpage', [App\Http\Controllers\EnergyCostController::class, 'editPage']);
Route::post('energy-cost-export', [App\Http\Controllers\EnergyCostController::class, 'export'])->name('energy-cost.export');
Route::resource('donor-cost', App\Http\Controllers\EnergyDonorCostController::class); 
Route::get('/delete-donor-cost', [App\Http\Controllers\EnergyDonorCostController::class, 'deleteEnergyDonorCost'])->name('deleteEnergyDonorCost');
Route::get('donor-cost/{id}/editpage', [App\Http\Controllers\EnergyDonorCostController::class, 'editPage']);

Route::resource('energy-generator-turbine', App\Http\Controllers\EnergyGeneratorTurbineController::class); 
Route::get('/delete-generator-community', [App\Http\Controllers\EnergyGeneratorTurbineController::class, 'deleteEnergyGenerator'])->name('deleteEnergyGenerator');
Route::resource('energy-turbine', App\Http\Controllers\EnergyTurbineController::class); 
Route::get('/delete-energy-turbine', [App\Http\Controllers\EnergyTurbineController::class, 'deleteEnergyTurbine'])->name('deleteEnergyTurbine');

Route::get('/household/household/autocomplete/{term}', [App\Http\Controllers\HouseholdController::class, 'autoComplete'])->name('autocomplete.households');

Route::post('missing-household-export', [App\Http\Controllers\HouseholdController::class, 'exportMissing'])->name('missing-household.export');
Route::get('/delete-community-water-source', [App\Http\Controllers\CommunityController::class, 'deleteCommunityWaterSources'])->name('deleteCommunityWaterSources');
Route::get('/delete-community-town', [App\Http\Controllers\CommunityController::class, 'deletecommunityNearbyTowns'])->name('deletecommunityNearbyTowns');
Route::get('/delete-community-settelement', [App\Http\Controllers\CommunityController::class, 'deleteCommunityNearbySettlements'])->name('deleteCommunityNearbySettlements');
Route::get('/delete-community-product', [App\Http\Controllers\CommunityController::class, 'deletecommunityProductTypes'])->name('deletecommunityProductTypes');
Route::get('/delete-compound', [App\Http\Controllers\CompoundController::class, 'deleteCompound'])->name('deleteCompound');

Route::resource('other-community', App\Http\Controllers\OtherCommunityController::class);

Route::get('/other-community/new', [App\Http\Controllers\HouseholdController::class, 'newCommunity']);

Route::resource('assigned-action', App\Http\Controllers\AssignedActionItemUserController::class);

Route::resource('room-grid', App\Http\Controllers\RoomGridController::class);

Route::resource('water-log', App\Http\Controllers\WaterSystemLogFrameController::class);
Route::post('water-log-export', [App\Http\Controllers\WaterSystemLogFrameController::class, 'export'])->name('water-log.export');
Route::get('/delete-log', [App\Http\Controllers\WaterSystemLogFrameController::class, 'deleteWaterLog'])->name('deleteWaterLog');
Route::get('water-log/{id}/editpage', [App\Http\Controllers\WaterSystemLogFrameController::class, 'editPage']);

Route::post('household-import', [App\Http\Controllers\HouseholdController::class, 'import'])->name('household.import');

Route::resource('water-request', App\Http\Controllers\WaterRequestSystemController::class);
Route::post('water-request-export', [App\Http\Controllers\WaterRequestSystemController::class, 'export'])->name('water-request.export');
Route::get('water-request/get_by_community/{community_id}', [App\Http\Controllers\WaterRequestSystemController::class, 'getRequestedByCommunity']);
Route::get('water-request/get_by_household/{household_public_id}/{is_household}', [App\Http\Controllers\WaterRequestSystemController::class, 'getDetailsByHouseholdPublic']);
Route::get('/delete-water-request', [App\Http\Controllers\WaterRequestSystemController::class, 'deleteRequestedWaterSystem'])->name('deleteWaterRequest');
Route::get('water-request/get_by_community/public/{community_id}', [App\Http\Controllers\WaterRequestSystemController::class, 'getPublicByCommunity']);
Route::get('/move-water-request', [App\Http\Controllers\WaterRequestSystemController::class, 'moveRequestedWaterSystem'])->name('moveWaterRequest');
Route::post('energy-request-export-progress', [App\Http\Controllers\WaterRequestSystemController::class, 'exportProgress'])->name('water-progress.export');

Route::resource('vending-point', App\Http\Controllers\VendingPointController::class);
Route::post('vendor-export', [App\Http\Controllers\VendingPointController::class, 'export'])->name('vending-point.export');
Route::get('vendor/community_town/{community_town}', [App\Http\Controllers\VendingPointController::class, 'getVendingPointPlace']);
Route::get('/delete-vendor', [App\Http\Controllers\VendingPointController::class, 'deleteVendor'])->name('deleteVendor');
Route::get('/delete-served-community', [App\Http\Controllers\VendingPointController::class, 'deleteServedCommunity'])->name('deleteServedCommunity');

Route::resource('young-holder', App\Http\Controllers\YoungHolderController::class);
Route::get('/delete-young-holder', [App\Http\Controllers\YoungHolderController::class, 'deleteyoungHolder'])->name('deleteyoungHolder');
Route::get('young-holder/community/{community_id}', [App\Http\Controllers\YoungHolderController::class, 'getYoungAndMainDetailsByCommunity']);
Route::get('young-holder/main_user/{energy_user_id}', [App\Http\Controllers\YoungHolderController::class, 'getMeterNumberByMain']);

Route::resource('camera-request', App\Http\Controllers\CameraRequestedController::class);
Route::post('camera-request-export', [App\Http\Controllers\CameraRequestedController::class, 'export'])->name('camera-request.export');
Route::get('/delete-camera-request', [App\Http\Controllers\CameraRequestedController::class, 'deleteCameraRequest'])->name('deleteCameraRequest');
Route::get('camera-request/has_camera/{community_id}', [App\Http\Controllers\CameraRequestedController::class, 'getCameraDetailsByCommunity']);
Route::get('camera-request/{id}/editpage', [App\Http\Controllers\CameraRequestedController::class, 'editPage']);
Route::post('all-incident-export', [App\Http\Controllers\MgIncidentController::class, 'exportAll'])->name('all-report-incident.export');

Route::resource('refrigerator-issue', App\Http\Controllers\RefrigeratorIssueController::class);
Route::post('refrigerator-issue-export', [App\Http\Controllers\RefrigeratorIssueController::class, 'export'])->name('refrigerator-issue.export');
Route::get('/delete-refri-issue', [App\Http\Controllers\RefrigeratorIssueController::class, 'deleteRefrigeratorIssue'])->name('deleteRefrigeratorIssue');
Route::get('/refrigerator-issue/get_by_action_category/{id}', [App\Http\Controllers\RefrigeratorIssueController::class, 'getRefrigeratorActionBasedOnCategory']);

Route::resource('refrigerator-action', App\Http\Controllers\RefrigeratorActionController::class);
Route::post('refrigerator-action-export', [App\Http\Controllers\RefrigeratorActionController::class, 'export'])->name('refrigerator-action.export');
Route::get('/delete-refri-action', [App\Http\Controllers\RefrigeratorActionController::class, 'deleteRefrigeratorMainAction'])->name('deleteRefrigeratorMainAction');

Route::resource('water-action', App\Http\Controllers\WaterActionController::class);
Route::get('/delete-water-action', [App\Http\Controllers\WaterActionController::class, 'deleteWaterMainAction'])->name('deleteWaterMainAction');
Route::post('water-action-export', [App\Http\Controllers\WaterActionController::class, 'export'])->name('water-action.export');

Route::resource('water-issue', App\Http\Controllers\WaterIssueController::class);
Route::get('/delete-water-issue', [App\Http\Controllers\WaterIssueController::class, 'deleteWaterMainIssue'])->name('deleteWaterMainIssue');
Route::post('water-issue-export', [App\Http\Controllers\WaterIssueController::class, 'export'])->name('water-issue.export');
Route::get('/water-issue/get_by_action_category/{id}', [App\Http\Controllers\WaterIssueController::class, 'getWaterActionBasedOnCategory']);

Route::get('/internet-issue/get_by_action_category/{id}', [App\Http\Controllers\InternetIssueController::class, 'getInternetActionBasedOnCategory']);

Route::resource('data-collection', App\Http\Controllers\DataCollectionController::class);
Route::post('data-collection-export', [App\Http\Controllers\DataCollectionController::class, 'export'])->name('data-collection.export');
Route::post('data-collection-export-household', [App\Http\Controllers\DataCollectionController::class, 'exportHousehold'])->name('data-collection.export-household');
Route::post('data-collection-export-all', [App\Http\Controllers\DataCollectionController::class, 'exportAll'])->name('data-collection.export-all');
Route::post('data-collection-import', [App\Http\Controllers\DataCollectionController::class, 'import'])->name('data-collection.import');
Route::post('data-collection-export-community', [App\Http\Controllers\DataCollectionController::class, 'exportCommunity'])->name('data-collection.export-communities');
Route::post('data-collection-import-ac', [App\Http\Controllers\DataCollectionController::class, 'importAc'])->name('data-collection.import-ac');
Route::post('data-collection-export-incident', [App\Http\Controllers\DataCollectionController::class, 'exportIncident'])->name('data-collection.export-incident');
Route::post('data-collection-export-displacement', [App\Http\Controllers\DataCollectionController::class, 'exportDisplacement'])->name('data-collection.export-displacement');
Route::post('data-collection-export-agriculture', [App\Http\Controllers\DataCollectionController::class, 'exportAgriculture'])->name('data-collection.export-agriculture');
Route::post('data-collection-export-requested', [App\Http\Controllers\DataCollectionController::class, 'exportRequestedHousehold'])->name('data-collection.export-requested');
Route::post('data-collection-export-requested-household', [App\Http\Controllers\DataCollectionController::class, 'exportRequestedHouseholds'])->name('data-collection.export-requested-household');
Route::post('data-collection-export-workshop', [App\Http\Controllers\DataCollectionController::class, 'exportWorkshop'])->name('data-collection.export-workshop');
Route::post('data-collection-import-requested', [App\Http\Controllers\DataCollectionController::class, 'importRequested'])->name('data-collection.import-requested');
Route::post('data-collection-export-all-community', [App\Http\Controllers\DataCollectionController::class, 'exportAllCommunities'])->name('data-collection.export-all-community');
Route::post('data-collection-import-community', [App\Http\Controllers\DataCollectionController::class, 'importCommunity'])->name('data-collection.import-community');

Route::get('household/compound/{id}', [App\Http\Controllers\HouseholdController::class, 'getCompounds']);

Route::resource('misc-household', App\Http\Controllers\MiscHouseholdController::class);
Route::get('/move-misc-household', [App\Http\Controllers\MiscHouseholdController::class, 'moveMISCHousehold'])->name('moveMISCHousehold');
Route::get('/move-misc-public', [App\Http\Controllers\MiscHouseholdController::class, 'moveMISCPublic'])->name('moveMISCPublic');
Route::post('misc-household-export', [App\Http\Controllers\MiscHouseholdController::class, 'export'])->name('misc-household.export');

Route::resource('postponed-household', App\Http\Controllers\PostponedHouseholdController::class);
Route::get('/move-postponed-household', [App\Http\Controllers\PostponedHouseholdController::class, 'movePostponedHousehold'])->name('movePostponedHousehold');

Route::resource('energy-delete-request', App\Http\Controllers\DeletedRequestedHouseholdController::class);
Route::get('/delete-energy-delete-request', [App\Http\Controllers\DeletedRequestedHouseholdController::class, 'deleteEnergyDeletedRequest'])->name('deleteEnergyDeletedRequest');
Route::get('/return-energy-delete-request', [App\Http\Controllers\DeletedRequestedHouseholdController::class, 'returnEnergyDeletedRequest'])->name('returnEnergyDeletedRequest');

Route::resource('all-maintenance', App\Http\Controllers\AllMaintenanceController::class);
Route::post('all-maintenance-export', [App\Http\Controllers\AllMaintenanceController::class, 'export'])->name('all-maintenance.export');

Route::get('/water-holder-delivery', [App\Http\Controllers\AllWaterController::class, 'checkboxDelivered'])->name('checkboxDelivered');
Route::get('/water-holder-complete', [App\Http\Controllers\AllWaterController::class, 'checkboxCompleted'])->name('checkboxCompleted');
Route::get('/water-holder-paid', [App\Http\Controllers\AllWaterController::class, 'checkboxPaid'])->name('checkboxPaid');

Route::resource('all-workshop', App\Http\Controllers\AllWorkshopsController::class);
Route::post('all-workshop-export', [App\Http\Controllers\AllWorkshopsController::class, 'export'])->name('all-workshop.export');
Route::post('all-workshop-import', [App\Http\Controllers\AllWorkshopsController::class, 'import'])->name('all-workshop.import');
Route::get('/delete-workshop-photo', [App\Http\Controllers\AllWorkshopsController::class, 'deleteWorkshopPhoto'])->name('deleteWorkshopPhoto');
Route::get('/delete-workshop-co-trainer', [App\Http\Controllers\AllWorkshopsController::class, 'deleteWorkshopCommunityCoTrainer'])->name('deleteWorkshopCommunityCoTrainer');

Route::resource('replacements', ReplacementController::class);
Route::post('replacements/destroy', [ReplacementController::class, 'destroy'])->name('replacements.destroy');
Route::post('/replacements/export', [ReplacementController::class, 'export'])->name('replacements.export');
Route::post('/replacements/store', [ReplacementController::class, 'store'])->name('replacements.store');
Route::get('/replacements/{id}/edit', [ReplacementController::class, 'edit'])->name('replacements.edit');
Route::post('/replacements/delete', [ReplacementController::class, 'destroy'])->name('replacements.delete');

Route::resource('camera-additions', CameraCommunityAdditionController::class);

Route::post('camera-additions/destroy', [CameraCommunityAdditionController::class, 'destroy'])->name('camera-additions.destroy');
Route::post('camera-additions/export', [CameraCommunityAdditionController::class, 'export'])->name('camera-additions.export');
Route::post('camera-additions/store', [CameraCommunityAdditionController::class, 'store'])->name('camera-additions.store');
Route::get('camera-additions/{id}/edit', [CameraCommunityAdditionController::class, 'edit'])->name('camera-additions.edit');
Route::post('camera-additions/delete', [CameraCommunityAdditionController::class, 'destroy'])->name('camera-additions.delete');


// New routes
Route::resource('all-incident', App\Http\Controllers\AllIncidentController::class);
Route::post('all-incident-export', [App\Http\Controllers\AllIncidentController::class, 'export'])->name('all-incident.export');
Route::get('/delete-all-incident', [App\Http\Controllers\AllIncidentController::class, 'deleteAllIncident'])->name('deleteAllIncident');
Route::get('/all-incident/get_energy_holder/{community_id}/{flag}', [App\Http\Controllers\AllIncidentController::class, 'getEnergyHolderSystemByCommunity']);
Route::get('/all-incident/get_incident_statuses/{incident_id}', [App\Http\Controllers\AllIncidentController::class, 'getIncidentStatusesByType']);
Route::get('/all-incident/get_water_holder/{community_id}/{flag}', [App\Http\Controllers\AllIncidentController::class, 'getWaterHolderSystemByCommunity']);
Route::get('/all-incident/get_internet_holder/{community_id}/{flag}', [App\Http\Controllers\AllIncidentController::class, 'getInternetHolderSystemByCommunity']);
Route::get('/update-water-tank/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updateTank'])->name('updateTank');
Route::get('/delete-water-tank', [App\Http\Controllers\WaterSystemController::class, 'deleteTank'])->name('deleteTank');
Route::get('/update-water-pipe/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updatePipe'])->name('updatePipe');
Route::get('/delete-water-pipe', [App\Http\Controllers\WaterSystemController::class, 'deletePipe'])->name('deletePipe');
Route::get('/update-water-pump/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updatePump'])->name('updatePump');
Route::get('/delete-water-pump', [App\Http\Controllers\WaterSystemController::class, 'deletePump'])->name('deletePump');
Route::get('/update-water-connector/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updateConnector'])->name('updateConnector');
Route::get('/delete-water-connector', [App\Http\Controllers\WaterSystemController::class, 'deleteConnector'])->name('deleteConnector');
Route::get('/update-water-filter/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updateFilter'])->name('updateFilter');
Route::get('/delete-water-filter', [App\Http\Controllers\WaterSystemController::class, 'deleteFilter'])->name('deleteFilter');
Route::get('/update-water-tap/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updateTap'])->name('updateTap');
Route::get('/delete-water-tap', [App\Http\Controllers\WaterSystemController::class, 'deleteTap'])->name('deleteTap');
Route::get('/update-water-valve/{id}/{units}/{cost}', [App\Http\Controllers\WaterSystemController::class, 'updateValve'])->name('updateValve');
Route::get('/delete-water-valve', [App\Http\Controllers\WaterSystemController::class, 'deleteValve'])->name('deleteValve');

Route::get('/update-internet-router/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateRouter'])->name('updateRouter');
Route::get('/update-internet-switch/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateSwitch'])->name('updateSwitch');
Route::get('/update-internet-controller/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateController'])->name('updateController');
Route::get('/update-internet-ap/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateAp'])->name('updateAp');
Route::get('/update-internet-ap-lite/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateApLite'])->name('updateApLite');
Route::get('/update-internet-ptp/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updatePtp'])->name('updatePtp');
Route::get('/update-internet-uisp/{id}/{units}/{cost}', [App\Http\Controllers\InternetSystemController::class, 'updateUisp'])->name('updateUisp');

Route::get('/update-energy-battery/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateBattery'])->name('updateBattery');
Route::get('/update-energy-battery-mount/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateBatteryMount'])->name('updateBatteryMount');
Route::get('/update-energy-pv/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updatePv'])->name('updatePv');
Route::get('/update-energy-pv-mount/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updatePvMount'])->name('updatePvMount');
Route::get('/update-energy-controller/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateChargeController'])->name('updateChargeController');
Route::get('/update-energy-inverter/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateInverter'])->name('updateInverter');
Route::get('/update-energy-relay-driver/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateRelayDriver'])->name('updateRelayDriver');
Route::get('/update-energy-load-relay/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateLoadRelay'])->name('updateLoadRelay');
Route::get('/update-energy-conditioner/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateConditioner'])->name('updateConditioner');
Route::get('/update-energy-bsp/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateBsp'])->name('updateBsp');
Route::get('/update-energy-bts/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateBts'])->name('updateBts');
Route::get('/update-energy-rcc/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateRcc'])->name('updateRcc');
Route::get('/update-energy-generator/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateGenerator'])->name('updateGenerator');
Route::get('/update-energy-logger/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateLogger'])->name('updateLogger');
Route::get('/update-energy-turbine/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateTurbine'])->name('updateTurbine');
Route::get('/update-energy-mcb-pv/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateMcbPv'])->name('updateMcbPv');
Route::get('/update-energy-mcb-controller/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateMcbController'])->name('updateMcbController');
Route::get('/update-energy-mcb-inverter/{id}/{units}/{cost}', [App\Http\Controllers\EnergySystemController::class, 'updateMcbInverter'])->name('updateMcbInverter');


Route::get('/energy-systems/{id}/components', [App\Http\Controllers\AllIncidentController::class, 'getSystemComponents']);