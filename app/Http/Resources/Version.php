<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Version extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file' => $this->file,
            'beta' => (bool)$this->beta,
            'changelog' => $this->changelog,
            'date' => $this->created_at->diffForHumans(),
        ];
    }
}
