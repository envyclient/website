<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Config extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'official' => (bool)$this->official,
            'public' => (bool)$this->public,
            'data' => json_decode($this->data),
            'favorites' => $this->favorites_count,
            'is_favorite' => $this->isFavoritedBy(auth()->user()),
            'user' => [
                'name' => $this->user->name
            ],
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
