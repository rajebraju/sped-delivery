<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\RestaurantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location'];
    // protected $casts = [
    //     'location' => 'point',
    // ];

    public function deliveryZones(): HasMany
    {
        return $this->hasMany(DeliveryZone::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

     protected static function newFactory()
    {
        return RestaurantFactory::new();
    }
}