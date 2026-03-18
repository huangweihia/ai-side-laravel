<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('文章信息')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => 
                                $set('slug', Str::slug($state))
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('summary')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('内容')
                    ->schema([
                        RichEditor::make('content')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('articles'),
                    ]),

                Forms\Components\Section::make('分类与设置')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_premium')
                            ->label('付费内容'),
                        Forms\Components\Toggle::make('is_published')
                            ->label('已发布'),
                        Forms\Components\DateTimePicker::make('published_at'),
                    ])->columns(3),

                Forms\Components\Section::make('SEO 设置')
                    ->schema([
                        Forms\Components\TextInput::make('meta_keywords')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(500)
                            ->rows(2),
                        Forms\Components\TextInput::make('source_url')
                            ->url()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('封面图')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('article-covers')
                            ->maxSize(5120),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_premium')
                    ->label('付费')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('发布')
                    ->boolean(),
                Tables\Columns\TextColumn::make('view_count')
                    ->sortable()
                    ->icon('heroicon-m-eye'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('付费内容'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('已发布'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
