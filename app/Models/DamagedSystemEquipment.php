<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamagedSystemEquipment extends Model
{
    use HasFactory;

    public function getModelName(): string {

        return $this->batteryMount?->model->model
            ?? $this->inverter?->model->inverter_model
            ?? $this->battery?->model->battery_model
            ?? $this->pv?->model->pv_model
            ?? $this->router?->model->model
            ?? $this->switch?->model->model
            ?? $this->controller?->model->model
            ?? $this->ap?->model->model
            ?? $this->aplite?->model->model
            ?? $this->generator?->model->generator_model
            ?? $this->tank?->model->model
            ?? $this->pipe?->model->model
            ?? $this->pump?->model->model
            ?? $this->valve?->model->model
            ?? $this->filter?->model->model
            ?? $this->connector?->model->model
            ?? 'Unknown Component';
    }
}