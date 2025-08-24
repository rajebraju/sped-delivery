<?php

namespace App\Services;

use App\Models\DeliveryZone;
use Illuminate\Support\Facades\DB;

class GeoService
{
    public function isPointInZone(array $point, DeliveryZone $zone): bool
    {
        $lat = $point['lat'];
        $lng = $point['lng'];

        if ($zone->type === 'radius') {
            // Ensure center exists
            if (!$zone->center) {
                return false;
            }

            $centerLat = $zone->center->getLat();
            $centerLng = $zone->center->getLng();

            // Calculate distance in **meters**
            $distanceQuery = DB::raw("
                ST_Distance_Sphere(
                    POINT({$centerLng}, {$centerLat}),
                    POINT({$lng}, {$lat})
                )
            ");

            $result = DB::table('delivery_zones')
                ->where('id', $zone->id)
                ->whereRaw($distanceQuery . ' <= ?', [$zone->radius_km * 1000])
                ->exists();

            return $result;
        }

        if ($zone->type === 'polygon') {
            $pointSql = "POINT({$lng}, {$lat})";
            return DB::table('delivery_zones')
                ->where('id', $zone->id)
                ->whereRaw("ST_Contains(area, {$pointSql})")
                ->exists();
        }

        return false;
    }
}