<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'user' => [
                'name' => $this->wallet->user->name
            ],
            'amount' => $this->amount,
            'meta' => $this->meta,
            'date' => $this->created_at->diffForHumans()
        ];
    }
}
