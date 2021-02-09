<?php

namespace App\Http\Livewire\Referral;

use App\Models\ReferralCode;
use Livewire\Component;
use Livewire\WithPagination;

class ListReferralCodes extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['REFERRAL_CODE_CREATED' => '$refresh'];

    public function render()
    {
        $codes = ReferralCode::with(['user:id,name', 'subscriptions'])
            ->orderBy('created_at')
            ->paginate(5);

        return view('livewire.referral.list-referral-codes', [
            'codes' => $codes
        ]);
    }
}
