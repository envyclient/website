<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'beta' => $this->beta,
            'changelog' => $this->changelog,
            'main_class' => $this->main_class,
        ];
    }
}
