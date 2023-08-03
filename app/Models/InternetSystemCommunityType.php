<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystemCommunityType extends Model
{
    use HasFactory;

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    } 

    public function InternetSystemType()
    {
        return $this->belongsTo(InternetSystemType::class, 'internet_system_type_id', 'id');
    } 
}
