<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'postalCode' => $this->postalCode,
            'categories' => $this->categories,
            'image' => json_decode($this->image),
            'featuredAt' => (string) $this->featuredAt,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
