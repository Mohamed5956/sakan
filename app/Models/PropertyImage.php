<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'image_path',
        'alt',
        'order',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
