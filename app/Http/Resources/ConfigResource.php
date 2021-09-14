<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    public function toArray($request): array
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
                'name' => $this->user->name,
            ],
            'version' => floatval(explode(' ', $this->version->name)[1]),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
