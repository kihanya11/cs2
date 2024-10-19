<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ActivateAccount extends Component
{
    public string $activation_code = '';

    public function render()
    {
        return view('livewire.pages.auth.activate-account')
        ->layout('layouts.guest');
    }

    public function activate(): void
    {
        $validated = $this->validate([
            'activation_code' => ['required', 'string', 'size:10'], // Adjust size as per your code length
        ]);
    
        $user = User::where('activation_code', $this->activation_code)->first();
    
        if (!$user) {
            throw ValidationException::withMessages([
                'activation_code' => 'Invalid activation code.',
            ]);
        }
    
        $user->is_active = true;
        $user->activation_code = null; // Clear the activation code after activation
        $user->save();
    
        // Optionally log in the user or redirect to login page
        $this->redirect(route('login'), navigate: true);
    }
}
