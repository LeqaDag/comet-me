<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceH2oAction extends Model
{
    use HasFactory;

    protected $fillable = ['type'];
}
