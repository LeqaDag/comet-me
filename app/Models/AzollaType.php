<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AzollaType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'azolla_types';

    protected $fillable = ['name', 'description', 'is_archived'];
}