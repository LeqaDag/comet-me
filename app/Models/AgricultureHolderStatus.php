<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureHolderStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'comet_id',
        'english_name',
        'arabic_name'
    ];
}
