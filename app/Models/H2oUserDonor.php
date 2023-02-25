<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class H2oUserDonor extends Model
{
    use HasFactory;

    protected $fillable = ['h2o_user_id', 'donor_id'];

    public function H2oUser()
    {
        return $this->belongsTo(H2oUser::class, 'h2o_user_id', 'id');
    }

    public function Donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
}
