<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Facades\Filament;

class OtpChallenge extends Component
{
    public $otp = '';

    public function mount()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->to(Filament::getLoginUrl());
        }

        if ($user->role !== 'developer') {
            return redirect()->to('/admin');
        }

        if (session('otp_verified')) {
            return redirect()->to('/admin');
        }
    }

    public function verify()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'developer') {
            return redirect()->to('/admin');
        }

        if ($this->otp === $user->otp_code && now()->lessThanOrEqualTo($user->otp_expires_at)) {
            // Success
            session(['otp_verified' => true]);
            
            // Clear OTP
            $user->update([
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            return redirect()->to('/admin');
        }

        // Failed
        Notification::make()
            ->title('Kode OTP Tidak Valid atau Kadaluarsa')
            ->danger()
            ->send();
            
        $this->otp = '';
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to(Filament::getLoginUrl());
    }

    public function render()
    {
        return view('livewire.otp-challenge')
            ->layout('filament-panels::components.layout.base', [
                'title' => 'Verifikasi OTP',
            ]);
    }
}
