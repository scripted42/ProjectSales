<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Models\Promo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Inventory & Sales';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Promo Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('hook')
                            ->label('Hook Sentence')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->maxLength(50),
                                Forms\Components\DateTimePicker::make('end_date')
                                    ->required(),
                            ]),
                        Forms\Components\FileUpload::make('image')
                            ->directory('promos')
                            ->disk('public')
                            ->visibility('public')
                            ->extensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->label('Background Image (Opsional)'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('leads_count')
                    ->counts('leads')
                    ->label('Total Leads'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
