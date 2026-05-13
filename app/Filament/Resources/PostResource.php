<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('posts/attachments'),
                    ])->columnSpan(2),

                Forms\Components\Section::make('Settings & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('posts/thumbnails')
                            ->imageEditor(),
                        Forms\Components\Select::make('category')
                            ->options([
                                'News' => 'News',
                                'Tips' => 'Tips',
                                'Promo' => 'Promo',
                                'Event' => 'Event',
                            ])
                            ->required()
                            ->default('News'),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'News' => 'info',
                        'Tips' => 'success',
                        'Promo' => 'warning',
                        'Event' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'News' => 'News',
                        'Tips' => 'Tips',
                        'Promo' => 'Promo',
                        'Event' => 'Event',
                    ]),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('promote')
                    ->label('Promosikan')
                    ->icon('heroicon-m-megaphone')
                    ->color('success')
                    ->visible(fn () => \App\Models\Setting::isPro())
                    ->modalHeading('Materi Promosi Instagram')
                    ->modalDescription('Bagikan berita/promo ini ke media sosial Anda.')
                    ->form([
                        Forms\Components\Placeholder::make('image_preview')
                            ->label('Gambar Berita/Promo')
                            ->content(fn ($record) => view('filament.components.image-preview', ['url' => asset('storage/' . $record->image)])),
                        Forms\Components\Textarea::make('caption')
                            ->label('Caption Berita (Salin)')
                            ->rows(8)
                            ->default(fn ($record) => "🔥 [" . strtoupper($record->category) . "] " . $record->title . "\n\nAda kabar terbaru untuk Anda! Klik link di bio untuk baca selengkapnya atau hubungi saya via WhatsApp sekarang! 🚀\n\n#promo #beritaterkini #hyundai #showroomautomotive")
                            ->helperText('Salin teks ini untuk caption Instagram/Story.'),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
