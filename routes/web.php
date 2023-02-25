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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/dashboard/analytics', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');
Route::get('/dashboard/ecommerce', $controller_path . '\dashboard\Ecommerce@index')->name('dashboard-ecommerce');


// locale
Route::get('lang/{locale}', $controller_path . '\language\LanguageController@swap');

// layout
Route::get('/layouts/collapsed-menu', $controller_path . '\layouts\CollapsedMenu@index')->name('layouts-collapsed-menu');
Route::get('/layouts/content-navbar', $controller_path . '\layouts\ContentNavbar@index')->name('layouts-content-navbar');
Route::get('/layouts/content-nav-sidebar', $controller_path . '\layouts\ContentNavSidebar@index')->name('layouts-content-nav-sidebar');
Route::get('/layouts/horizontal', $controller_path . '\layouts\Horizontal@index')->name('dashboard-analytics');
Route::get('/layouts/vertical', $controller_path . '\layouts\Vertical@index')->name('dashboard-analytics');
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// apps
Route::get('/app/calendar', $controller_path . '\apps\Calendar@index')->name('app-calendar');
Route::get('/app/invoice/list', $controller_path . '\apps\InvoiceList@index')->name('app-invoice-list');
Route::get('/app/invoice/preview', $controller_path . '\apps\InvoicePreview@index')->name('app-invoice-preview');
Route::get('/app/invoice/print', $controller_path . '\apps\InvoicePrint@index')->name('app-invoice-print');
Route::get('/app/invoice/edit', $controller_path . '\apps\InvoiceEdit@index')->name('app-invoice-edit');
Route::get('/app/invoice/add', $controller_path . '\apps\InvoiceAdd@index')->name('app-invoice-add');
Route::get('/app/user/list', $controller_path . '\apps\UserList@index')->name('app-user-list');
Route::get('/app/user/view/account', $controller_path . '\apps\UserViewAccount@index')->name('app-user-view-account');
Route::get('/app/user/view/security', $controller_path . '\apps\UserViewSecurity@index')->name('app-user-view-security');
Route::get('/app/user/view/billing', $controller_path . '\apps\UserViewBilling@index')->name('app-user-view-billing');
Route::get('/app/user/view/notifications', $controller_path . '\apps\UserViewNotifications@index')->name('app-user-view-notifications');
Route::get('/app/user/view/connections', $controller_path . '\apps\UserViewConnections@index')->name('app-user-view-connections');
Route::get('/app/access-roles', $controller_path . '\apps\AccessRoles@index')->name('app-access-roles');
Route::get('/app/access-permission', $controller_path . '\apps\AccessPermission@index')->name('app-access-permission');

// pages
Route::get('/pages/profile-user', $controller_path . '\pages\UserProfile@index')->name('pages-profile-user');
Route::get('/pages/profile-teams', $controller_path . '\pages\UserTeams@index')->name('pages-profile-teams');
Route::get('/pages/profile-projects', $controller_path . '\pages\UserProjects@index')->name('pages-profile-projects');
Route::get('/pages/profile-connections', $controller_path . '\pages\UserConnections@index')->name('pages-profile-connections');
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-security', $controller_path . '\pages\AccountSettingsSecurity@index')->name('pages-account-settings-security');
Route::get('/pages/account-settings-billing', $controller_path . '\pages\AccountSettingsBilling@index')->name('pages-account-settings-billing');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::get('/pages/faq', $controller_path . '\pages\Faq@index')->name('pages-faq');
Route::get('/pages/help-center-landing', $controller_path . '\pages\HelpCenterLanding@index')->name('pages-help-center-landing');
Route::get('/pages/help-center-categories', $controller_path . '\pages\HelpCenterCategories@index')->name('pages-help-center-categories');
Route::get('/pages/help-center-article', $controller_path . '\pages\HelpCenterArticle@index')->name('pages-help-center-article');
Route::get('/pages/pricing', $controller_path . '\pages\Pricing@index')->name('pages-pricing');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');
Route::get('/pages/misc-comingsoon', $controller_path . '\pages\MiscComingSoon@index')->name('pages-misc-comingsoon');
Route::get('/pages/misc-not-authorized', $controller_path . '\pages\MiscNotAuthorized@index')->name('pages-misc-not-authorized');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/login-cover', $controller_path . '\authentications\LoginCover@index')->name('auth-login-cover');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::get('/auth/register-cover', $controller_path . '\authentications\RegisterCover@index')->name('auth-register-cover');
Route::get('/auth/register-multisteps', $controller_path . '\authentications\RegisterMultiSteps@index')->name('auth-register-multisteps');
Route::get('/auth/verify-email-basic', $controller_path . '\authentications\VerifyEmailBasic@index')->name('auth-verify-email-basic');
Route::get('/auth/verify-email-cover', $controller_path . '\authentications\VerifyEmailCover@index')->name('auth-verify-email-cover');
Route::get('/auth/reset-password-basic', $controller_path . '\authentications\ResetPasswordBasic@index')->name('auth-reset-password-basic');
Route::get('/auth/reset-password-cover', $controller_path . '\authentications\ResetPasswordCover@index')->name('auth-reset-password-cover');
Route::get('/auth/forgot-password-basic', $controller_path . '\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');
Route::get('/auth/forgot-password-cover', $controller_path . '\authentications\ForgotPasswordCover@index')->name('auth-forgot-password-cover');
Route::get('/auth/two-steps-basic', $controller_path . '\authentications\TwoStepsBasic@index')->name('auth-two-steps-basic');
Route::get('/auth/two-steps-cover', $controller_path . '\authentications\TwoStepsCover@index')->name('auth-two-steps-cover');

// wizard example
Route::get('/wizard/ex-checkout', $controller_path . '\wizard_example\Checkout@index')->name('wizard-ex-checkout');
Route::get('/wizard/ex-property-listing', $controller_path . '\wizard_example\PropertyListing@index')->name('wizard-ex-property-listing');
Route::get('/wizard/ex-create-deal', $controller_path . '\wizard_example\CreateDeal@index')->name('wizard-ex-create-deal');

// modal
Route::get('/modal-examples', $controller_path . '\modal\ModalExample@index')->name('modal-examples');

// cards
Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');
Route::get('/cards/advance', $controller_path . '\cards\CardAdvance@index')->name('cards-advance');
Route::get('/cards/statistics', $controller_path . '\cards\CardStatistics@index')->name('cards-statistics');
Route::get('/cards/analytics', $controller_path . '\cards\CardAnalytics@index')->name('cards-analytics');
Route::get('/cards/actions', $controller_path . '\cards\CardActions@index')->name('cards-actions');

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/extended/ui-avatar', $controller_path . '\extended_ui\Avatar@index')->name('extended-ui-avatar');
Route::get('/extended/ui-blockui', $controller_path . '\extended_ui\BlockUI@index')->name('extended-ui-blockui');
Route::get('/extended/ui-drag-and-drop', $controller_path . '\extended_ui\DragAndDrop@index')->name('extended-ui-drag-and-drop');
Route::get('/extended/ui-media-player', $controller_path . '\extended_ui\MediaPlayer@index')->name('extended-ui-media-player');
Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-star-ratings', $controller_path . '\extended_ui\StarRatings@index')->name('extended-ui-star-ratings');
Route::get('/extended/ui-sweetalert2', $controller_path . '\extended_ui\SweetAlert@index')->name('extended-ui-sweetalert2');
Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');
Route::get('/extended/ui-timeline-basic', $controller_path . '\extended_ui\TimelineBasic@index')->name('extended-ui-timeline-basic');
Route::get('/extended/ui-timeline-fullscreen', $controller_path . '\extended_ui\TimelineFullscreen@index')->name('extended-ui-timeline-fullscreen');
Route::get('/extended/ui-tour', $controller_path . '\extended_ui\Tour@index')->name('extended-ui-tour');
Route::get('/extended/ui-treeview', $controller_path . '\extended_ui\Treeview@index')->name('extended-ui-treeview');
Route::get('/extended/ui-misc', $controller_path . '\extended_ui\Misc@index')->name('extended-ui-misc');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');
Route::get('/icons/font-awesome', $controller_path . '\icons\FontAwesome@index')->name('icons-font-awesome');

// form elements
Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');
Route::get('/forms/custom-options', $controller_path . '\form_elements\CustomOptions@index')->name('forms-custom-options');
Route::get('/forms/editors', $controller_path . '\form_elements\Editors@index')->name('forms-editors');
Route::get('/forms/file-upload', $controller_path . '\form_elements\FileUpload@index')->name('forms-file-upload');
Route::get('/forms/pickers', $controller_path . '\form_elements\Picker@index')->name('forms-pickers');
Route::get('/forms/selects', $controller_path . '\form_elements\Selects@index')->name('forms-selects');
Route::get('/forms/sliders', $controller_path . '\form_elements\Sliders@index')->name('forms-sliders');
Route::get('/forms/switches', $controller_path . '\form_elements\Switches@index')->name('forms-switches');
Route::get('/forms/extras', $controller_path . '\form_elements\Extras@index')->name('forms-extras');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');
Route::get('/form/layouts-sticky', $controller_path . '\form_layouts\StickyActions@index')->name('form-layouts-sticky');

// form wizards
Route::get('/form/wizard-numbered', $controller_path . '\form_wizard\Numbered@index')->name('form-wizard-numbered');
Route::get('/form/wizard-icons', $controller_path . '\form_wizard\Icons@index')->name('form-wizard-icons');
Route::get('/form/validation', $controller_path . '\form_validation\Validation@index')->name('form-validation');

// tables
Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');
Route::get('/tables/datatables-basic', $controller_path . '\tables\DatatableBasic@index')->name('tables-datatables-basic');
Route::get('/tables/datatables-advanced', $controller_path . '\tables\DatatableAdvanced@index')->name('tables-datatables-advanced');
Route::get('/tables/datatables-extensions', $controller_path . '\tables\DatatableExtensions@index')->name('tables-datatables-extensions');

// charts
Route::get('/charts/apex', $controller_path . '\charts\ApexCharts@index')->name('charts-apex');
Route::get('/charts/chartjs', $controller_path . '\charts\ChartJs@index')->name('charts-chartjs');

// maps
Route::get('/maps/leaflet', $controller_path . '\maps\Leaflet@index')->name('maps-leaflet');

// laravel example
Route::get('/laravel/user-management', [UserManagement::class, 'UserManagement'])->name('laravel-example-user-management');
Route::resource('/user-list', UserManagement::class);


Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/admin', 'Auth\AdminController@index')->name('admin.dashboard');
});

Route::prefix('admin')->group(function() {
    Route::get('/login',[App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login',[App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::get('/logout',[App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/user/{id}',[App\Http\Controllers\Auth\AdminLoginController::class, 'user']);
    Route::get('/profit/search',[App\Http\Controllers\Auth\AdminLoginController::class, 'searchProfits']);
});

Route::get('/login',[App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login',[App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('/logout',[App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('community', App\Http\Controllers\CommunityController::class);
Route::get('community/destory/{id}', App\Http\Controllers\CommunityController::class.'@destroy');
Route::resource('household', App\Http\Controllers\HouseholdController::class);
Route::get('community/get_by_region/{region_id}', [App\Http\Controllers\CommunityController::class, 'getByRegion']);
Route::get('household/household/{name}', [App\Http\Controllers\HouseholdController::class, 'newProfession']);
Route::get('household/community/{region_id}', [App\Http\Controllers\HouseholdController::class, 'newCommunity']);
Route::get('export', [App\Http\Controllers\HouseholdController::class, 'exportPdf'])->name('export');
Route::get('community/{id}/photo', App\Http\Controllers\CommunityController::class.'@photo');
Route::get('community/{id}/map', App\Http\Controllers\CommunityController::class.'@map');


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

Route::get('household/get_by_community/{community_id}', [App\Http\Controllers\HouseholdController::class, 'getByCommunity']);
Route::resource('donor', App\Http\Controllers\DonorController::class);
Route::get('donor/destory/{id}', App\Http\Controllers\DonorController::class.'@destroy');
Route::resource('community-donor', App\Http\Controllers\CommunityDonorController::class);
Route::resource('region', App\Http\Controllers\RegionController::class);
Route::get('region/get_region/{region_id}', [App\Http\Controllers\RegionController::class, 'getByRegion']);
Route::get('region/get_sub_region/{region_id}/{sub_region_id}', [App\Http\Controllers\RegionController::class, 'getBySubRegion']);
Route::get('region/edit_region/{id}', [App\Http\Controllers\RegionController::class, 'updateRegion']);
Route::get('energy_user/get_by_household/{household_id}', [App\Http\Controllers\EnergyUserController::class, 'getByHousehold']);

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
Route::get('ac-household/energy-user/get_by_energy_type/{energy_type_id}', [App\Http\Controllers\EnergyUserController::class, 'getEnergySystemByType']);
Route::get('ac-household/energy-user/shared_household/{community_id}/{user_id}', [App\Http\Controllers\EnergyUserController::class, 'getSharedHousehold']);
Route::get('ac-household/energy-user/get_misc/{misc}', [App\Http\Controllers\EnergyUserController::class, 'getMiscCommunity']);

Route::resource('energy-system', App\Http\Controllers\EnergySystemController::class);
Route::resource('water-system', App\Http\Controllers\WaterSystemController::class);

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

Route::resource('household-meter', App\Http\Controllers\HouseholdMeterController::class);
Route::get('household-meter/get_households/{id}', [App\Http\Controllers\HouseholdMeterController::class, 'getHouseholds'])->name('getHouseholds');
Route::get('/delete-household-meter', [App\Http\Controllers\HouseholdMeterController::class, 'deleteHouseholdMeter'])->name('deleteHouseholdMeter');

Route::resource('all-water', App\Http\Controllers\AllWaterController::class);
Route::resource('shared-h2o', App\Http\Controllers\SharedWaterController::class);
Route::resource('water-public', App\Http\Controllers\WaterPublicStructureController::class);

Route::resource('water-maintenance', App\Http\Controllers\H2oMaintenanceCallController::class);

Route::resource('internet-system', App\Http\Controllers\InternetSystemController::class);
Route::get('internet-system/{id}/showPage', [App\Http\Controllers\InternetSystemController::class, 'showPage']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'showMainPage']);

Route::get('allMeter/donor/{id}/editDonor', [App\Http\Controllers\AllEnergyController::class, 'editDonor']);
Route::get('/delete-maintenance', [App\Http\Controllers\H2oMaintenanceCallController::class, 'deleteMaintenanceWater'])->name('deleteMaintenanceWater');
