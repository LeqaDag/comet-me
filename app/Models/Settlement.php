<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'hebrew_name', 'region_id'];
    
    public function Region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
