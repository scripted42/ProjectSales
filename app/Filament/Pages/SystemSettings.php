<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SystemSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'System License';
    protected static ?string $navigationGroup = 'Developer Only';
    protected static string $view = 'filament.pages.system-settings';

    public ?string $otpInput = '';
    public ?string $mothershipToken = '';
    public ?string $mothershipSecret = '';
    public bool $isVerified = false;

    public function mount()
    {
        $this->isVerified = session()->get('developer_verified', false);
        $this->mothershipToken = Setting::where('key', 'mothership_token')->first()?->value;
        $this->mothershipSecret = Setting::where('key', 'mothership_secret')->first()?->value;
    }

    public function saveToken()
    {
        Setting::updateOrCreate(['key' => 'mothership_token'], ['value' => $this->mothershipToken]);
        Setting::updateOrCreate(['key' => 'mothership_secret'], ['value' => $this->mothershipSecret]);
        
        Notification::make()
            ->title('Mothership Credentials Saved')
            ->success()
            ->send();
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Only show to the developer (you)
        return auth()->user()?->role === 'developer';
    }

    public function verifyOtp()
    {
        if ($this->otpInput === '123456') {
            $this->isVerified = true;
            session()->put('developer_verified', true);
            
            Notification::make()
                ->title('Access Granted')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Invalid OTP Code')
                ->danger()
                ->send();
        }
    }

    public function logoutDeveloper()
    {
        $this->isVerified = false;
        session()->forget('developer_verified');
    }

    public function checkConnection()
    {
        try {
            $response = Http::get('http://127.0.0.1:8001/api/verify', [
                'domain' => '127.0.0.1:8000'
            ]);

            if ($response->successful()) {
                Notification::make()
                    ->title('Terhubung ke Mothership')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Koneksi Gagal: ' . ($response->json()['message'] ?? 'Unknown Error'))
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Mothership Offline')
                ->danger()
                ->send();
        }
    }

    public function toggleLicense()
    {
        Notification::make()
            ->title('Action Disabled')
            ->body('Silakan ubah paket langsung dari Panel Mothership (Port 8001).')
            ->warning()
            ->send();
    }
}
