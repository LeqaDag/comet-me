<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicStructure extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'public_structure_category_id1', 
        'public_structure_category_id2',
        'public_structure_category_id3'];

    public function Category1()
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id1', 'id');
    }

    public function Category2()
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id2', 'id');
    }

    public function Category3()
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id3', 'id');
    }
}
