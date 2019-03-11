<?php

function calcCoordinates($longitude, $latitude, $radius = 20)
{
    $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
    $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
    $lat_min = $latitude - ($radius / 69);
    $lat_max = $latitude + ($radius / 69);

    return [
        'min' => [
            'lat' => $lat_min,
            'lng' => $lng_min,
        ],
        'max' => [
            'lat' => $lat_max,
            'lng' => $lng_max,
        ],
    ];
}

