<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\DeliveryManFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DeliveryMan extends Model
{
     use HasFactory;
    protected $fillable = ['name', 'location', 'is_available'];
    // protected $casts = [
    //     'location' => 'point',
    // ];

    protected static function newFactory()
    {
        return DeliveryManFactory::new();
    }
}