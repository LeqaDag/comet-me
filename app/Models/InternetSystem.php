<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystem extends Model
{
    use HasFactory;

    protected $fillable = ['system_name', 'internet_system_type_id'];


    public function InternetSystemType()
    {
        return $this->belongsTo(InternetSystemType::class, 'internet_system_type_id', 'id');
    }
}
