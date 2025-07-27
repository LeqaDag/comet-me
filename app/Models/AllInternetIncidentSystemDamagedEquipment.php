<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInternetIncidentSystemDamagedEquipment extends Model
{
    use HasFactory;

    public function router() 
    {
        return $this->belongsTo(RouterInternetSystem::class, 'router_internet_system_id');
    }

    public function switch()
    {
        return $this->belongsTo(SwitchInternetSystem::class, 'switch_internet_system_id');
    }

    public function controller()
    {
        return $this->belongsTo(ControllerInternetSystem::class, 'controller_internet_system_id');
    }

    public function ptp()
    {
        return $this->belongsTo(PtpInternetSystem::class, 'ptp_internet_system_id');
    }

    public function ap()
    {
        return $this->belongsTo(ApInternetSystem::class, 'ap_internet_system_id');
    }

    public function aplite()
    {
        return $this->belongsTo(ApLiteInternetSystem::class, 'ap_lite_internet_system_id');
    }

    public function uisp()
    {
        return $this->belongsTo(UispInternetSystem::class, 'uisp_internet_system_id');
    }

    public function connector()
    {
        return $this->belongsTo(ConnectorInternetSystem::class, 'connector_internet_system_id');
    }

    public function electrician()
    {
        return $this->belongsTo(ElectricianInternetSystem::class, 'electrician_internet_system_id');
    }

    public function networkCabinetComponent()
    {
        return $this->belongsTo(NetworkCabinetComponent::class, 'network_cabinet_component_id');
    }
}
