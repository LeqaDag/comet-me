<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystem extends Model
{
    use HasFactory;

    public function communityLink()
    {
        return $this->hasOne(InternetSystemCommunity::class, 'internet_system_id');
    }

    public function Community()
    {
        return $this->hasOneThrough(
            Community::class,
            InternetSystemCommunity::class,
            'internet_system_id', 
            'id', 
            'id',                 
            'community_id'      
        );
    }

    public function Compound()
    {
        return $this->hasOneThrough(
            Compound::class,
            InternetSystemCommunity::class,
            'internet_system_id', 
            'id', 
            'id',                 
            'compound_id'      
        );
    }

    public function InternetSystemCommunity()
    {
        return $this->belongsTo(InternetSystemCommunity::class, 'internet_system_id', 'id');
    }

    public function routers() 
    {
        return $this->belongsToMany(
            Router::class, 
            'router_internet_systems', 'internet_system_id', 'router_id')
        ->withPivot('id', 'router_costs');
    }

    public function ptps()
    {
        return $this->belongsToMany(
            InternetPtp::class, 
            'ptp_internet_systems', 'internet_system_id', 'internet_ptp_id')
        ->withPivot('id', 'ptp_costs');
    }

    public function uisps()
    {
        return $this->belongsToMany(
            InternetUisp::class, 
            'uisp_internet_systems', 'internet_system_id', 'internet_uisp_id')
        ->withPivot('id', 'uisp_costs');
    }

    public function aps()
    {
        return $this->belongsToMany(
            InternetAp::class, 
            'ap_internet_systems', 'internet_system_id', 'internet_ap_id')
        ->withPivot('id', 'ap_costs');
    }

    public function aplites()
    {
        return $this->belongsToMany(
            InternetAp::class, 
            'ap_lite_internet_systems', 'internet_system_id', 'internet_ap_id')
        ->withPivot('id', 'ap_lite_costs');
    }

    public function switches()
    {
        return $this->belongsToMany(
            Switche::class, 
            'switch_internet_systems', 'internet_system_id', 'switch_id')
        ->withPivot('id', 'switch_costs');
    }

    public function controllers()
    {
        return $this->belongsToMany(
            InternetController::class, 
            'controller_internet_systems', 'internet_system_id', 'internet_controller_id')
        ->withPivot('id', 'controller_costs');
    }

    public function connectors()
    {
        return $this->belongsToMany(
            InternetConnector::class, 
            'connector_internet_systems', 'internet_system_id', 'internet_connector_id')
        ->withPivot('id', 'connector_costs');
    }

    public function electricians()
    {
        return $this->belongsToMany(
            InternetElectrician::class, 
            'electrician_internet_systems', 'internet_system_id', 'internet_electrician_id')
        ->withPivot('id', 'electrician_costs');
    }


    public function networkCabinets()
    {
        return $this->belongsToMany(NetworkCabinet::class, 'network_cabinet_internet_systems')
            ->withPivot('id', 'cost') 
            ->withTimestamps();
    }

    public function components()
    {
        return $this->hasManyThrough(
            NetworkCabinetComponent::class,
            NetworkCabinetInternetSystem::class,
            'internet_system_id',            // Foreign key on NetworkCabinetInternetSystem table
            'network_cabinet_internet_system_id', // Foreign key on NetworkCabinetComponent table
            'id',                           // Local key on InternetSystem table
            'id'                            // Local key on NetworkCabinetInternetSystem table
        );
    }
}
