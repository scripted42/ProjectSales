<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Video Configuration')
                    ->description('Manage landing page video and promotional popup.')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'youtube' => 'Section Home Video (YouTube Only)',
                                'popup' => 'Popup Promotion (MP4 / YouTube)',
                            ])
                            ->default('youtube')
                            ->required()
                            ->live(),
                        
                        Forms\Components\TextInput::make('title')
                            ->placeholder('e.g., Hyundai Stargazer X Official Trailer')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label('YouTube URL')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->url()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'youtube')
                            ->required(fn (Forms\Get $get) => $get('type') === 'youtube'),

                        Forms\Components\FileUpload::make('video_path')
                            ->label('Video File (.mp4)')
                            ->directory('videos')
                            ->acceptedFileTypes(['video/mp4'])
                            ->maxSize(102400) // 100MB (just in case they still want to try upload)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'popup')
                            ->helperText('Gunakan ini jika file di bawah 100MB. Jika lebih besar, gunakan link di bawah.')
                            ->preserveFilenames(),

                        Forms\Components\TextInput::make('external_video_url')
                            ->label('Link Video (MP4 / YouTube)')
                            ->placeholder('https://www.youtube.com/watch?v=... atau https://domain.com/video.mp4')
                            ->url()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'popup')
                            ->helperText('Masukkan link YouTube atau link langsung ke file .mp4.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'youtube' => 'danger',
                        'popup' => 'success',
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => $state?->format('Y-m-d H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
