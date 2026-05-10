<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Mothership Control';
    protected static ?string $navigationGroup = 'SaaS Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Site Information')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Forms\Components\TextInput::make('domain')
                                ->label('Client Domain')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('example.com')
                                ->helperText('Enter domain without http:// or https://')
                                ->dehydrateStateUsing(fn ($state) => str_replace(['http://', 'https://', '/'], '', strtolower($state))),
                            Forms\Components\TextInput::make('site_name')
                                ->label('Site Name')
                                ->placeholder('Hyundai Showroom ABC'),
                        ])->columns(2),

                    Forms\Components\Wizard\Step::make('Licensing & Plan')
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            Forms\Components\Select::make('plan')
                                ->options([
                                    'regular' => 'Regular',
                                    'pro' => 'Pro Max',
                                ])
                                ->default('regular')
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'active' => 'Active',
                                    'suspended' => 'Suspended',
                                ])
                                ->default('active')
                                ->required(),
                            Forms\Components\TextInput::make('expired_at')
                                ->label('Expiry Date')
                                ->placeholder('YYYY-MM-DD')
                                ->default(now()->addYear()->format('Y-m-d'))
                                ->required(),
                        ])->columns(3),

                    Forms\Components\Wizard\Step::make('Security & Sync')
                        ->icon('heroicon-o-lock-closed')
                        ->schema([
                            Forms\Components\TextInput::make('token')
                                ->label('Mothership Token')
                                ->default(fn () => \Illuminate\Support\Str::random(32))
                                ->required()
                                ->readOnly()
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('generateToken')
                                        ->icon('heroicon-m-arrow-path')
                                        ->action(fn ($set) => $set('token', \Illuminate\Support\Str::random(32)))
                                ),
                            Forms\Components\TextInput::make('secret_key')
                                ->label('HMAC Secret Key')
                                ->default(fn () => \Illuminate\Support\Str::random(64))
                                ->required()
                                ->readOnly()
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('generateSecret')
                                        ->icon('heroicon-m-arrow-path')
                                        ->action(fn ($set) => $set('secret_key', \Illuminate\Support\Str::random(64)))
                                ),
                        ])->columns(1),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('site_name')
                    ->label('Site Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pro' => 'primary',
                        'regular' => 'gray',
                    }),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'suspended' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('expired_at')
                    ->label('Expires')
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::parse($state)->format('d M Y') : '-')
                    ->sortable()
                    ->color(fn ($record) => \Illuminate\Support\Carbon::parse($record->expired_at)->isPast() ? 'danger' : 'gray'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('copy_credentials')
                    ->label('Copy Credentials')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('gray')
                    ->modalHeading('Client Credentials')
                    ->modalDescription('Use these credentials to connect the client site to Mothership.')
                    ->form([
                        Forms\Components\TextInput::make('token')
                            ->default(fn (Client $record) => $record->token)
                            ->readOnly()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('copyToken')
                                    ->icon('heroicon-m-clipboard')
                                    ->action(function ($state, $livewire) {
                                        $livewire->js("window.navigator.clipboard.writeText('{$state}');");
                                        Notification::make()->title('Token copied!')->success()->send();
                                    })
                            ),
                        Forms\Components\TextInput::make('secret_key')
                            ->default(fn (Client $record) => $record->secret_key)
                            ->readOnly()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('copySecret')
                                    ->icon('heroicon-m-clipboard')
                                    ->action(function ($state, $livewire) {
                                        $livewire->js("window.navigator.clipboard.writeText('{$state}');");
                                        Notification::make()->title('Secret key copied!')->success()->send();
                                    })
                            ),
                    ])
                    ->modalSubmitAction(false),
                Tables\Actions\EditAction::make(),
                Action::make('ping')
                    ->icon('heroicon-m-signal')
                    ->color('info')
                    ->action(function (Client $record) {
                        try {
                            $data = ['action' => 'ping'];
                            $signature = hash_hmac('sha256', json_encode($data), $record->secret_key);

                            $response = Http::withHeaders([
                                'X-Mothership-Token' => $record->token,
                                'X-Mothership-Signature' => $signature,
                            ])->timeout(5)->post("http://{$record->domain}/api/mothership-sync", $data);

                            if ($response->successful()) {
                                Notification::make()->title('Client Online')->success()->send();
                            } else {
                                Notification::make()->title('Client Offline or Invalid Signature')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()->title('Connection Failed')->danger()->send();
                        }
                    }),
                Action::make('toggle_status')
                    ->label(fn (Client $record) => $record->status === 'active' ? 'Suspend' : 'Activate')
                    ->icon(fn (Client $record) => $record->status === 'active' ? 'heroicon-m-x-circle' : 'heroicon-m-check-circle')
                    ->color(fn (Client $record) => $record->status === 'active' ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (Client $record) {
                        $newStatus = $record->status === 'active' ? 'suspended' : 'active';
                        $targetAction = $newStatus === 'active' ? 'activate' : 'suspend';
                        $data = ['action' => $targetAction];

                        try {
                            $signature = hash_hmac('sha256', json_encode($data), $record->secret_key);

                            $response = Http::withHeaders([
                                'X-Mothership-Token' => $record->token,
                                'X-Mothership-Signature' => $signature,
                            ])->post("http://{$record->domain}/api/mothership-sync", $data);

                            if ($response->successful()) {
                                $record->update(['status' => $newStatus]);
                                Notification::make()->title("Client " . ucfirst($newStatus))->success()->send();
                            } else {
                                Notification::make()->title('Sync Failed (Invalid Signature)')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()->title('Client Unreachable')->danger()->send();
                        }
                    }),
                Action::make('sync_license')
                    ->label('Sync License')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Push License Data')
                    ->modalDescription('This will update the plan and expiry date on the client site.')
                    ->action(function (Client $record) {
                        $data = [
                            'action' => 'update_license',
                            'plan' => $record->plan,
                            'expired_at' => $record->expired_at,
                        ];

                        try {
                            $signature = hash_hmac('sha256', json_encode($data), $record->secret_key);

                            $response = Http::withHeaders([
                                'X-Mothership-Token' => $record->token,
                                'X-Mothership-Signature' => $signature,
                            ])->post("http://{$record->domain}/api/mothership-sync", $data);

                            if ($response->successful()) {
                                Notification::make()->title('License Synced Successfully')->success()->send();
                            } else {
                                Notification::make()->title('Sync Failed')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()->title('Client Unreachable')->danger()->send();
                        }
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
