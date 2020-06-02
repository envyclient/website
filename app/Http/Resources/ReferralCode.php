<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'uses' => [
                'total' => $this->users()->count(),
                'today' => $this->users()->whereDate('created_at', Carbon::today())->count(),
            ],
            'user' => [
                'name' => $this->user->name
            ],
            'date' => $this->created_at->diffForHumans()
        ];
    }
}
