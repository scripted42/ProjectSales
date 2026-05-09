<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestDriveBookingResource\Pages;
use App\Models\TestDriveBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestDriveBookingResource extends Resource
{
    protected static ?string $model = TestDriveBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Select::make('car_id')
                            ->relationship('car', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('booking_date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('car.name')
                    ->label('Unit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hot_lead')
                    ->label('Priority')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $views = \App\Models\SiteLog::where('ip_address', $record->ip_address)
                            ->where('car_id', $record->car_id)
                            ->where('log_type', 'visit')
                            ->count();
                        
                        return $views > 3 ? 'HOT LEAD' : null;
                    })
                    ->color('danger')
                    ->visible(fn ($record) => $record !== null),
                Tables\Columns\TextColumn::make('booking_date')
                    ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::parse($state)->format('Y-m-d') : null)
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestDriveBookings::route('/'),
            'create' => Pages\CreateTestDriveBooking::route('/create'),
            'edit' => Pages\EditTestDriveBooking::route('/{record}/edit'),
        ];
    }
}
