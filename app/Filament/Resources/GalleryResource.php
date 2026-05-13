<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Filament\Resources\GalleryResource\RelationManagers;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->required(),
                Forms\Components\TextInput::make('caption')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'handover' => 'Handover Photo',
                        'showroom' => 'Showroom Photo',
                    ])
                    ->required()
                    ->default('handover'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('caption')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
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
                Tables\Actions\Action::make('promote')
                    ->label('Promosikan')
                    ->icon('heroicon-m-megaphone')
                    ->color('success')
                    ->visible(fn () => \App\Models\Setting::isPro())
                    ->modalHeading('Materi Promosi Instagram')
                    ->modalDescription('Salin caption di bawah dan gunakan gambar ini untuk postingan Instagram/WhatsApp Anda.')
                    ->form([
                        Forms\Components\Placeholder::make('image_preview')
                            ->label('Gambar yang akan dipromosikan')
                            ->content(fn ($record) => view('filament.components.image-preview', ['url' => asset('storage/' . $record->image)])),
                        Forms\Components\Textarea::make('caption')
                            ->label('Caption Instagram (Salin)')
                            ->rows(8)
                            ->default(fn ($record) => "📸 Update Terbaru dari Showroom!\n\n" . ($record->caption ?? "Unit siap dipinang!") . "\n\nCek detail selengkapnya di website kami. Hubungi saya sekarang untuk penawaran terbaik! 🚀\n\n#hyundai #mobilbaru #promoautomotive #showroom")
                            ->helperText('Klik & salin teks ini, lalu gunakan untuk postingan Anda.'),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
