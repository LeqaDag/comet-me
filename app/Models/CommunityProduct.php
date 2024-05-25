<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_type_id', 'community_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function ProductType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }
}
