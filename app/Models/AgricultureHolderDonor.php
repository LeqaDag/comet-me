<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureHolderDonor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'agriculture_holder_id',
        'donor_id',
    ];

    public function agricultureHolder()
    {
        return $this->belongsTo(AgricultureHolder::class, 'agriculture_holder_id');
    }


    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }
}
