<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'name',
        'beds',
        'notes',
    ];

    protected $casts = [
        'beds' => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
