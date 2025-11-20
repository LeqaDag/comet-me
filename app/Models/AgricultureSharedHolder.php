<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureSharedHolder extends Model
{
    use HasFactory;
    protected $table = 'agriculture_shared_holders';

    protected $fillable = [
        'agriculture_holder_id',
        'household_id',
        'size_of_herds',
        'is_archived'
    ];
    
    protected $casts = [
        'size_of_herds' => 'integer',
        'is_archived' => 'boolean'
    ];
    
    public function agricultureHolder()
    {
        return $this->belongsTo(AgricultureHolder::class, 'agriculture_holder_id');
    }
    public function household()
    {
        return $this->belongsTo(Household::class, 'household_id');
    }
}
