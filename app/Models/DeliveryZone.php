<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryZone extends Model
{
    protected $fillable = ['restaurant_id', 'type', 'radius_km', 'center', 'area'];
    // protected $casts = [
    //     'center' => 'point',
    //     'area' => 'polygon',
    // ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}