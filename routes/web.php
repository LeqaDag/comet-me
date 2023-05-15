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


Route::resource('initial-household', App\Http\Controllers\InitialHouseholdController::class);
Route::get('/initial/ac', [App\Http\Controllers\InitialHouseholdController::class, 'initialToAcSurveyHousehold'])->name('initialToAcSurveyHousehold');
 
Route::resource('ac-household', App\Http\Controllers\AcHouseholdController::class);
Route::get('/ac/served', [App\Http\Controllers\AcHouseholdController::class, 'acToServedSurveyHousehold'])->name('acToServedSurveyHousehold');
Route::get('/ac/sub/household', [App\Http\Controllers\AcHouseholdController::class, 'acSubHousehold'])->name('acSubHousehold');
Route::get('/ac/shared/household', [App\Http\Controllers\AcHouseholdController::class, 'acSubHouseholdSave'])->name('acSubHouseholdSave');
Route::get('/ac/main/household', [App\Http\Controllers\AcHouseholdController::class, 'acMainHousehold'])->name('acMainHousehold');

Route::resource('served-household', App\Http\Controllers\ServedHouseholdController::class);

Route::resource('photo', App\Http\Controllers\PhotoController::class);
Route::resource('initial-community', App\Http\Controllers\InitialCommunityController::class);
Route::resource('ac-community', App\Http\Controllers\AcCommunityController::class);
Route::resource('served-community', App\Http\Controllers\ServedCommunityController::class);
Route::resource('water-user', App\Http\Controllers\WaterUserController::class);
Route::get('water-user/get_water_source/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getGridSource']);
Route::get('/delete-water-user', [App\Http\Controllers\WaterUserController::class, 'deleteWaterUser'])->name('deleteWaterUser');
Route::post('water-user-export', [App\Http\Controllers\WaterUserController::class, 'export'])->name('water-user.export');

Route::get('household/get_by_community/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getByCommunity']);
Route::resource('donor', App\Http\Controllers\DonorController::class);
Route::get('donor/destory/{id}', App\Http\Controllers\DonorController::class.'@destroy');
Route::resource('community-donor', App\Http\Controllers\CommunityDonorController::class);

Route::get('region/get_region/{region_id}', [App\Http\Controllers\RegionController::class, 'getByRegion']);
Route::get('region/get_sub_region/{region_id}/{sub_region_id}', [App\Http\Controllers\RegionController::class, 'getBySubRegion']);
Route::get('region/edit_region/{id}', [App\Http\Controllers\RegionController::class, 'updateRegion']); 
Route::get('energy_user/get_by_household/{household_id}', [App\Http\Controllers\EnergyUserController::class, 'getByHousehold']);
Route::get('energy_public/get_by_public/{public_id}', [App\Http\Controllers\WaterPublicStructureController::class, 'getByPublic']);
Route::get('energy_user/get_by_community/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergyUserByCommunity']);
Route::get('energy_public/get_by_community/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getPublicByCommunity']);

Route::resource('sub-region', App\Http\Controllers\SubRegionController::class);
Route::resource('sub-sub-region', App\Http\Controllers\SubSubRegionController::class);
Route::get('sub-region/edit_sub_region/{id}', [App\Http\Controllers\SubRegionController::class, 'updateSubRegion']);

Route::post('/update-sub', [App\Http\Controllers\SubRegionController::class, 'updateSubRegion'])->name('updateSubRegion');
Route::get('/delete-sub', [App\Http\Controllers\SubRegionController::class, 'deleteSubRegion'])->name('deleteSubRegion');
Route::get('/getSubRegionData/{id}', [App\Http\Controllers\SubRegionController::class, 'getSubRegionData'])->name('getSubRegionData');
Route::get('/getRegionData/{id}', [App\Http\Controllers\SubRegionController::class, 'getRegionData'])->name('getRegionData');
Route::get('/getAllSubRegion', [App\Http\Controllers\SubRegionController::class, 'getAllSubRegion'])->name('getAllSubRegion');
Route::get('/delete-region', [App\Http\Controllers\RegionController::class, 'deleteRegion'])->name('deleteRegion');

Route::resource('energy-user', App\Http\Controllers\EnergyUserController::class);
Route::get('ac-household/energy-user/get_by_community/{community_id}/{misc}', [App\Http\Controllers\EnergyUserController::class, 'getHouseholdByCommunity']);
Route::get('ac-household/energy-user/get_by_energy_type/{energy_type_id}/{community_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergySystemByType']);
Route::get('ac-household/energy-user/shared_household/{community_id}/{user_id}', [App\Http\Controllers\EnergyUserController::class, 'getSharedHousehold']);
Route::get('ac-household/energy-user/get_misc/{misc}', [App\Http\Controllers\EnergyUserController::class, 'getMiscCommunity']);
Route::post('energy-user-export', [App\Http\Controllers\EnergyUserController::class, 'export'])->name('energy-user.export');

Route::resource('energy-system', App\Http\Controllers\EnergySystemController::class);
Route::resource('water-system', App\Http\Controllers\WaterSystemController::class);
Route::get('water_user/get_by_community/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getWaterUserByCommunity']);
Route::get('water_public/get_by_community/{community_id}', [App\Http\Controllers\WaterUserController::class, 'getPublicByCommunity']);

Route::get('/details/incident', [App\Http\Controllers\HomeController::class, 'incidentDetails'])->name('incidentDetails');
Route::get('/water/chart/', [App\Http\Controllers\WaterUserController::class, 'chartWater'])->name('chartWater');
Route::get('/details/chart', [App\Http\Controllers\WaterUserController::class, 'waterChartDetails'])->name('waterChartDetails');
Route::get('/delete-household', [App\Http\Controllers\HouseholdController::class, 'deleteHousehold'])->name('deleteHousehold');
Route::get('household/{id}/editpage', [App\Http\Controllers\HouseholdController::class, 'editPage']);

Route::resource('all-meter', App\Http\Controllers\AllEnergyController::class);
Route::get('/allMeter/{id}', [App\Http\Controllers\AllEnergyController::class, 'getEnergyUserData'])->name('getEnergyUserData');
Route::get('/allMeter/info/{id}', [App\Http\Controllers\AllEnergyController::class, 'updateEnergyUserData'])->name('updateEnergyUserData');
Route::get('/allMeter/donor/{id}', [App\Http\Controllers\AllEnergyController::class, 'getEnergyUserDonors'])->name('getEnergyUserDonors');

Route::resource('energy-public', App\Http\Controllers\EnergyPublicStructureController::class);
Route::resource('comet-meter', App\Http\Controllers\EnergyCometMeterController::class);
Route::get('energy-public/get_by_community/{community_id}', [App\Http\Controllers\EnergyPublicStructureController::class, 'getByCommunity']);
Route::get('/delete-public', [App\Http\Controllers\EnergyPublicStructureController::class, 'deleteEnergyPublic'])->name('deleteEnergyPublic');
Route::get('/delete-comet-meter', [App\Http\Controllers\EnergyCometMeterController::class, 'deleteCometMeter'])->name('deleteCometMeter');

Route::resource('internet-user', App\Http\Controllers\InternetUserController::class);
Route::get('/details/fbs/incident', [App\Http\Controllers\EnergySystemController::class, 'incidentFbsDetails'])->name('incidentFbsDetails');
Route::post('internet-user-export', [App\Http\Controllers\InternetUserController::class, 'export'])->name('internet-user.export');

Route::resource('household-meter', App\Http\Controllers\HouseholdMeterController::class);
Route::get('household-meter/get_households/{id}', [App\Http\Controllers\HouseholdMeterController::class, 'getHouseholds'])->name('getHouseholds');
Route::get('/delete-household-meter', [App\Http\Controllers\HouseholdMeterController::class, 'deleteHouseholdMeter'])->name('deleteHouseholdMeter');

Route::resource('all-water', App\Http\Controllers\AllWaterController::class);
Route::resource('shared-h2o', App\Http\Controllers\SharedWaterController::class);
Route::resource('water-public', App\Http\Controllers\WaterPublicStructureController::class);
Route::get('all-water/{id}/editpage', [App\Http\Controllers\AllWaterController::class, 'editPage']);
Route::get('shared-h2o/get_by_community/{community_id}', [App\Http\Controllers\SharedWaterController::class, 'getH2oUsersByCommunity']);
Route::get('/delete-shared-h2o', [App\Http\Controllers\SharedWaterController::class, 'deleteSharedWaterUser'])->name('deleteSharedWaterUser');
Route::get('water-public/get_by_community/{community_id}', [App\Http\Controllers\WaterPublicStructureController::class, 'getPublicByCommunity']);
Route::get('allMeter/{id}/editpage', [App\Http\Controllers\AllEnergyController::class, 'editPage']);

Route::resource('water-maintenance', App\Http\Controllers\H2oMaintenanceCallController::class);
Route::post('water-maintenance-export', [App\Http\Controllers\H2oMaintenanceCallController::class, 'export'])->name('water-maintenance.export');

Route::resource('internet-system', App\Http\Controllers\InternetSystemController::class);
Route::get('internet-system/{id}/showPage', [App\Http\Controllers\InternetSystemController::class, 'showPage']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'showMainPage']);

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
Route::get('energy-maintenance/get_system/{system}', [App\Http\Controllers\EnergyMaintenanceCallController::class, 'getMaintenanceAction']);

Route::resource('new-energy-maintenance', App\Http\Controllers\NewEnergyMaintenanceCallController::class);
Route::get('/delete-new-energy-maintenance', [App\Http\Controllers\NewEnergyMaintenanceCallController::class, 'deleteNewMaintenanceEnergy'])->name('deleteNewMaintenanceEnergy');
Route::post('new-energy-maintenance-export', [App\Http\Controllers\NewEnergyMaintenanceCallController::class, 'export'])->name('new-energy-maintenance.export');

Route::resource('refrigerator-user', App\Http\Controllers\RefrigeratorHolderController::class);
Route::get('/delete-refrigerator', [App\Http\Controllers\RefrigeratorHolderController::class, 'deleteRefrigeratorHolder'])->name('deleteRefrigeratorHolder');
Route::post('refrigerator-export', [App\Http\Controllers\RefrigeratorHolderController::class, 'export'])->name('refrigerator.export');

Route::resource('refrigerator-maintenance', App\Http\Controllers\RefrigeratorMaintenanceCallController::class);
Route::get('/delete-refrigerator-maintenance', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'deleteRefrigerator'])->name('deleteRefrigerator');
Route::post('refrigerator-maintenance-export', [App\Http\Controllers\RefrigeratorMaintenanceCallController::class, 'export'])->name('refrigerator-maintenance.export');
Route::get('refrigerator-user/get_by_community/{community_id}', [App\Http\Controllers\RefrigeratorHolderController::class, 'getHouseholdByCommunity']);
Route::get('refrigerator-public/get_by_community/{community_id}', [App\Http\Controllers\RefrigeratorHolderController::class, 'getPublicByCommunity']);

Route::resource('mg-incident', App\Http\Controllers\MgIncidentController::class);
Route::get('/delete-mg-incident', [App\Http\Controllers\MgIncidentController::class, 'deleteMgIncident'])->name('deleteMgIncident');
Route::post('mg-incident-export', [App\Http\Controllers\MgIncidentController::class, 'export'])->name('mg-incident.export');

Route::resource('fbs-incident', App\Http\Controllers\FbsIncidentController::class);
Route::get('/delete-fbs-incident', [App\Http\Controllers\FbsIncidentController::class, 'deleteFbsIncident'])->name('deleteFbsIncident');
Route::post('fbs-incident-export', [App\Http\Controllers\FbsIncidentController::class, 'export'])->name('fbs-incident.export');

Route::resource('water-incident', App\Http\Controllers\WaterIncidentController::class);
Route::get('/delete-water-incident', [App\Http\Controllers\WaterIncidentController::class, 'deleteWaterIncident'])->name('deleteWaterIncident');
Route::post('water-incident-export', [App\Http\Controllers\WaterIncidentController::class, 'export'])->name('water-incident.export');

Route::post('quality-result-export', [App\Http\Controllers\WaterQualityResultController::class, 'export'])->name('quality-result.export');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('region', App\Http\Controllers\RegionController::class); 
});

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    Route::resource('quality-result', WaterQualityResultController::class);

    /**
     * Home Routes
     */
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['middleware' => ['guest']], function() {
        
        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@showLoginForm')->name('user.login');
        Route::post('/login', 'LoginController@login')->name('login');
    });

    Route::group(['middleware' => ['auth']], function() {

        /**
         * Logout Routes
         */
        Route::get('/logout', 'LoginController@logout')->name('logout');


        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);
    });
});