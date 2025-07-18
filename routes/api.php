<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/ 
 
Route::resource('household', App\Http\Controllers\Api\HouseholdController::class);
Route::resource('internet-holder', App\Http\Controllers\Api\InternetHolderController::class);
Route::resource('old-internet-holder', App\Http\Controllers\Api\OldInternetHoldersController::class);
Route::resource('energy-meter', App\Http\Controllers\Api\AllEnergyMeterController::class);
Route::resource('all-data', App\Http\Controllers\Api\AllEnergyHolderController::class);
Route::resource('actions', App\Http\Controllers\Api\AllActionsController::class);
Route::resource('data', App\Http\Controllers\Api\AllSystemDevicesController::class);
Route::resource('ticket', App\Http\Controllers\Api\AllTicketsController::class);
Route::resource('incident', App\Http\Controllers\Api\AllInicdentTypeController::class);