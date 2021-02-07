<?php

namespace App\Http\Livewire;

use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;

class UsersStats extends Component
{
    public string $period = 'all';

    public function render()
    {
        $stats = [];

        switch ($this->period) {
            case 'all':
            {
                $stats = [
                    'total' => User::count(),
                    'subscriptions' => Subscription::count(),
                ];
                break;
            }
            case 'today':
            {
                $stats = [
                    'total' => User::whereDate('created_at', today())->count(),
                    'subscriptions' => Subscription::whereDate('created_at', today())->count(),
                ];
                break;
            }
            case 'week':
            {
                $stats = [
                    'total' => User::whereDate('created_at', '>=', today()->subWeek())->count(),
                    'subscriptions' => Subscription::whereDate('created_at', '>=', today()->subWeek())->count(),
                ];
                break;
            }
            case 'month':
            {
                $stats = [
                    'total' => User::whereDate('created_at', '>=', today()->subMonth())->count(),
                    'subscriptions' => Subscription::whereDate('created_at', '>=', today()->subMonth())->count(),
                ];
                break;
            }
        }
        
        return view('livewire.users-stats', [
            'stats' => $stats,
        ]);
    }

}
