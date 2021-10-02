<?php

namespace App\Http\Livewire\Admin\Referral;

use App\Models\ReferralCode;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListReferralCodes extends Component
{
    use WithPagination;

    protected $listeners = ['REFERRAL_CODE_CREATED' => '$refresh'];

    public bool $showUsersModal = false;
    public $showingUsers = [];

    public bool $showInvoicesModal = false;
    public $showingInvoices = [];

    public function showUsersModal(int $code): void
    {
        $this->showUsersModal = true;
        $this->showingUsers = User::with('subscription')
            ->where('referral_code_id', $code)
            ->get();
    }

    public function showInvoicesModal(ReferralCode $code): void
    {
        $this->showInvoicesModal = true;
        $this->showingInvoices = $code->invoices()
            ->with('user')
            ->get();
    }

    public function render()
    {
        // get all the referral codes
        $codes = ReferralCode::query()
            ->with(['user:id,name,email', 'subscriptions'])
            ->orderBy('created_at')
            ->get();

        return view('livewire.admin.referral.list-referral-codes', compact('codes'));
    }
}
