<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureInstallationType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'agriculture_installation_types';

    protected $fillable = [
        'english_name',
        'arabic_name',
        'is_archived'
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'is_archived' => 'boolean'
    ];
}
