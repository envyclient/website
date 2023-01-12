<?php

namespace App\Http\Livewire\Admin\Referral;

use App\Models\ReferralCode;
use App\Models\User;
use Livewire\Component;

class CreateReferralCode extends Component
{
    public string $user = '';

    public string $code = '';

    protected array $rules = [
        'user' => ['required', 'string', 'exists:users,name'],
        'code' => ['required', 'string', 'min:3', 'max:15', 'alpha_dash', 'unique:referral_codes'],
    ];

    public function render()
    {
        return view('livewire.admin.referral.create-referral-code');
    }

    public function submit()
    {
        $data = $this->validate();

        // getting the name beholder
        $user = User::where('name', $data['user'])->first();

        // inserting into the db
        ReferralCode::create([
            'user_id' => $user->id,
            'code' => $data['code'],
        ]);

        // reset fields
        $this->user = '';
        $this->code = '';

        $this->emit('REFERRAL_CODE_CREATED');
        $this->smallNotify('Referral Code Created.');
    }
}
