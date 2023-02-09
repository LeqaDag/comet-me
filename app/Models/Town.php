<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'region_id'];
    
    public function Region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}