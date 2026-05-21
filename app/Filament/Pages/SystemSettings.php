<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class SystemSettings extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'System Settings';
    protected static ?string $navigationGroup = 'System Control';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'System & AI Settings';
    protected static ?string $slug = 'system-settings';
    protected static string $view = 'filament.pages.system-settings';
    
    // Menu ini muncul untuk role developer dan sales (client)
    public static function shouldRegisterNavigation(): bool
    {
        return in_array(auth()->user()?->role, ['developer', 'sales']);
    }

    public ?array $data = [];

    public function mount(): void
    {
        $encryptedKey = Setting::get('ai_api_key');
        $decryptedKey = '';
        if ($encryptedKey) {
            try {
                $decryptedKey = Crypt::decryptString($encryptedKey);
            } catch (\Exception $e) {
                $decryptedKey = '';
            }
        }

        $this->form->fill([
            'site_name' => Setting::get('site_name', 'Hyundai Showroom'),
            'site_logo' => Setting::get('site_logo'),
            'ai_provider' => Setting::get('ai_provider', 'openrouter'),
            'ai_model' => Setting::get('ai_model', 'qwen/qwen-2-7b-instruct:free'),
            'ai_api_key' => $decryptedKey,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Branding')
                    ->description('Atur identitas utama website di sini.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama Showroom / Brand')
                            ->required()
                            ->placeholder('Contoh: Hyundai Gowa Surabaya'),
                        FileUpload::make('site_logo')
                            ->label('Logo Utama')
                            ->image()
                            ->directory('settings')
                            ->helperText('Gunakan logo transparan (PNG) untuk hasil terbaik.'),
                    ])->columns(2),

                Section::make('AutoShow AI Insights')
                    ->description('Hubungkan website dengan model kecerdasan buatan (AI) untuk analisis dashboard secara pintar.')
                    ->schema([
                        Select::make('ai_provider')
                            ->label('AI Provider')
                            ->options([
                                'disabled' => 'Nonaktifkan AI (Gunakan Saran Statis)',
                                'openrouter' => 'OpenRouter (Qwen / Llama)',
                                'deepseek' => 'DeepSeek',
                                'gemini' => 'Google Gemini 1.5',
                            ])
                            ->required()
                            ->reactive(),
                        TextInput::make('ai_model')
                            ->label('Model AI')
                            ->placeholder('Contoh: qwen/qwen-2-7b-instruct:free')
                            ->helperText('Tentukan nama model yang digunakan.')
                            ->required(fn ($get) => $get('ai_provider') !== 'disabled')
                            ->visible(fn ($get) => $get('ai_provider') !== 'disabled'),
                        TextInput::make('ai_api_key')
                            ->label('API Key')
                            ->password()
                            ->placeholder('Masukkan API Key Anda')
                            ->helperText('Biarkan kosong jika ingin menggunakan API Key default dari sistem (.env)')
                            ->visible(fn ($get) => $get('ai_provider') !== 'disabled'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($key === 'ai_api_key') {
                if (empty($value)) {
                    // Hapus jika kosong agar fallback ke env
                    Setting::where('key', 'ai_api_key')->delete();
                    continue;
                } else {
                    // Enkripsi API Key sebelum disimpan
                    $value = Crypt::encryptString($value);
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully!')
            ->send();
    }
}
