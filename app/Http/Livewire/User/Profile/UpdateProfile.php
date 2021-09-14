<?php

namespace App\Http\Livewire\User\Profile;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateProfile extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount()
    {
        $this->resetInputFields(auth()->user());
    }

    public function render()
    {
        $user = auth()->user();
        return view('livewire.user.profile.update-profile', compact('user'));
    }

    public function submit()
    {
        $user = auth()->user();

        $this->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        if ($this->email !== $user->email && $user instanceof MustVerifyEmail) {

            // update the user information
            $user->forceFill([
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => null,
            ])->save();

            // resend the email verification
            $user->sendEmailVerificationNotification();

            $message = 'Profile updated. Please check your email for a verification link.';
        } else {
            $user->forceFill([
                'name' => $this->name,
                'email' => $this->email,
            ])->save();

            $message = 'Profile updated.';
        }

        $this->done($user, $message);
    }

    private function done(User $user, string $message)
    {
        // show small notification
        $this->smallNotify($message);

        // reset the inputs
        $this->resetInputFields($user);

        // update the profile component
        $this->emit('PROFILE_UPDATE');
    }

    private function resetInputFields(User $user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }

}
