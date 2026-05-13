<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class SystemSettings extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Developer Settings';
    protected static ?string $navigationGroup = 'System Control';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'System & Brand Settings';
    protected static ?string $slug = 'system-settings';
    protected static string $view = 'filament.pages.system-settings';
    
    // Menu ini hanya muncul untuk role developer
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'developer';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', 'Hyundai Showroom'),
            'site_logo' => Setting::get('site_logo'),
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
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
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
