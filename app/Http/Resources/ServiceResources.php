<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ServiceResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'title' => ucwords($this->title),
            'excerpt' => Str::limit($this->description, 60),
            'description' => ucwords($this->description),
            'address' => $this->address,
            'state' => $this->state,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'human_readable_date' => $this->created_at->diffForHumans(),
            'created_at' => $this->created_at
        ];
    }
}
