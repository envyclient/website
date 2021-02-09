<?php

namespace App\Http\Livewire\Referral;

use App\Models\ReferralCode;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListReferralCodes extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['REFERRAL_CODE_CREATED' => '$refresh'];

    public bool $showModal = false;
    public $showingUsers = [];

    public function showModal($code): void
    {
        $this->showModal = true;
        $this->showingUsers = User::with('subscription')
            ->where('referral_code_id', $code)
            ->get();
    }

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
