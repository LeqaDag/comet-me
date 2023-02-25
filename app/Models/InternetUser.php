<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetUser extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'internet_status_id',
        'start_date', 'number_of_contract', 'number_of_people'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function InternetStatus()
    {
        return $this->belongsTo(InternetStatus::class, 'internet_status_id', 'id');
    }
}
