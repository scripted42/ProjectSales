<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultantResource\Pages;
use App\Filament\Resources\ConsultantResource\RelationManagers;
use App\Models\Consultant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultantResource extends Resource
{
    protected static ?string $model = Consultant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Team & Profile';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->directory('consultants')
                    ->avatar(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\Textarea::make('address')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('bio')
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Social Media')
                    ->description('Masukkan link profil sosial media Anda')
                    ->schema([
                        Forms\Components\TextInput::make('instagram')
                            ->prefix('https://instagram.com/')
                            ->placeholder('username'),
                        Forms\Components\TextInput::make('facebook')
                            ->prefix('https://facebook.com/')
                            ->placeholder('username'),
                        Forms\Components\TextInput::make('tiktok')
                            ->prefix('https://tiktok.com/@')
                            ->placeholder('username'),
                    ])->columns(3),

                Forms\Components\Section::make('Location & Maps')
                    ->description('Masukkan URL Iframe dari Google Maps')
                    ->schema([
                        Forms\Components\Textarea::make('maps_embed')
                            ->label('Google Maps Embed Code')
                            ->placeholder('<iframe src="..."></iframe>')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultants::route('/'),
            'create' => Pages\CreateConsultant::route('/create'),
            'edit' => Pages\EditConsultant::route('/{record}/edit'),
        ];
    }
}
