<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [
        'id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($service){
            $service->user_id = auth()->check() ? auth()->id() : User::all()->random()->id;
        });
    }

    public function scopeDistance($query, $from_latitude, $from_longitude, $distance)
    {
        $between_coords = calcCoordinates($from_longitude, $from_latitude, $distance);

        return $query
            ->where(function ($q) use ($between_coords) {
                $q->whereBetween('longitude', [$between_coords['min']['lng'], $between_coords['max']['lng']]);
            })
            ->where(function ($q) use ($between_coords) {
                $q->whereBetween('latitude', [$between_coords['min']['lat'], $between_coords['max']['lat']]);
            });
    }
}
