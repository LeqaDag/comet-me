<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystemType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_year', 'upgrade_year'];

}