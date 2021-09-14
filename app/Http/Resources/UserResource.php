<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'api_token' => $this->api_token,
            'date' => $this->created_at->diffForHumans(),
            'subscription' => [
                'name' => $this->subscription->plan->name,
                'days' => $this->subscription->end_date->diffInDays(),
            ],
        ];
    }
}
