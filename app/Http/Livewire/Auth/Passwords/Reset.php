<?php

namespace App\Http\Livewire\Auth\Passwords;

use App\Models\User;
use App\Traits\ValidationRules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class Reset extends Component
{
    use ValidationRules;

    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount($token)
    {
        $this->email = request()->query('email', '');
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.auth.passwords.reset')->extends('layouts.guest');
    }

    public function submit()
    {
        $this->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => $this->passwordRules(),
        ]);

        $response = Password::broker()->reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
            ],
            function (User $user, string $password) {
                $user->password = Hash::make($password);

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                auth()->login($user);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            //session()->flash(trans($response));
            return redirect(route('home'));
        }

        $this->addError('email', trans($response));
    }
}
