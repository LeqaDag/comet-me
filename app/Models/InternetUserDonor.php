<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetUserDonor extends Model
{
    use HasFactory;

    protected $fillable = ['donor_id', 'internet_user_id'];

    public function Donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }

    public function InternetUser()
    {
        return $this->belongsTo(InternetUser::class, 'internet_user_id', 'id');
    }
}
