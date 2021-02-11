<?php

namespace App\Http\Livewire\User;

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
        return view('livewire.user.update-profile')
            ->with('user', auth()->user());
    }

    public function submit()
    {
        $user = auth()->user();
        $message = '';

        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $image = 'https://avatar.tobi.sh/avatar/' . md5(strtolower(trim($this->email))) . '.svg?text=' . strtoupper(substr($this->name, 0, 2));
        if ($this->email !== $user->email &&
            $user instanceof MustVerifyEmail) {

            // update the user information
            $user->forceFill([
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => null,
                'image' => $image,
            ])->save();

            // resend the email verification
            $user->sendEmailVerificationNotification();

            $message = 'Profile updated. Please check your email for a verification link.';
        } else {
            $user->forceFill([
                'name' => $this->name,
                'email' => $this->email,
                'image' => $image,
            ])->save();

            $message = 'Profile updated.';
        }

        session()->flash('message', $message);
        $this->resetInputFields($user);

        $this->emit('PROFILE_UPDATE');
    }

    private function resetInputFields(User $user)
    {
        $this->name = $user->name;
        $this->email = $user->email;
    }
}
