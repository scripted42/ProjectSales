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
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('domain')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('example.com'),
                        Forms\Components\TextInput::make('site_name')
                            ->placeholder('My Client Site'),
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
                        Forms\Components\DatePicker::make('expired_at')
                            ->default(now()->addYear()),
                        Forms\Components\TextInput::make('token')
                            ->default('TEST_TOKEN')
                            ->helperText('Security token for API communication'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('site_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->color(fn (string $state): string => match ($state) {
                        'pro' => 'success',
                        'regular' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('expired_at')
                    ->getStateUsing(fn ($record) => $record->expired_at ? \Carbon\Carbon::parse($record->expired_at)->format('d M Y') : 'N/A'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('ping')
                    ->icon('heroicon-m-signal')
                    ->color('info')
                    ->action(function (Client $record) {
                        try {
                            $response = Http::withHeaders([
                                'X-Mothership-Token' => $record->token,
                            ])->timeout(5)->post("http://{$record->domain}/api/mothership-sync", [
                                'action' => 'ping'
                            ]);

                            if ($response->successful()) {
                                Notification::make()->title('Client Online')->success()->send();
                            } else {
                                Notification::make()->title('Client Offline or Invalid Token')->danger()->send();
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

                        try {
                            $response = Http::withHeaders([
                                'X-Mothership-Token' => $record->token,
                            ])->post("http://{$record->domain}/api/mothership-sync", [
                                'action' => $targetAction
                            ]);

                            if ($response->successful()) {
                                $record->update(['status' => $newStatus]);
                                Notification::make()->title("Client " . ucfirst($newStatus))->success()->send();
                            } else {
                                Notification::make()->title('Sync Failed')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()->title('Client Unreachable')->danger()->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
