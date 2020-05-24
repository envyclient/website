<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralCode extends JsonResource
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
            'code' => $this->code,
            'referrals_count' => $this->users()->count(),
            'user' => [
                'name' => $this->user->name
            ],
            'date' => $this->created_at->diffForHumans()
        ];
    }
}
