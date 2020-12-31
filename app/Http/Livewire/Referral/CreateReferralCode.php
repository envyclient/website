<?php

namespace App\Http\Livewire\Referral;

use App\Models\ReferralCode;
use App\Models\User;
use Livewire\Component;

class CreateReferralCode extends Component
{
    public $user;
    public $code;

    protected $rules = [
        'user' => [
            'required',
            'string',
            'exists:users,name'
        ],
        'code' => [
            'required',
            'string',
            'min:3',
            'max:15',
            'alpha_dash',
            'unique:referral_codes'
        ],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.referral.create-referral-code');
    }

    public function submit()
    {
        $validatedData = $this->validate();

        // getting the name beholder
        $user = User::where('name', $validatedData['user'])->first();

        // inserting into the db
        ReferralCode::create([
            'user_id' => $user->id,
            'code' => $validatedData['code'],
        ]);

        $this->user = '';
        $this->code = '';

        $this->emit('REFERRAL_CODE_CREATED');
    }
}
