<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = '文章管理';
    protected static ?string $modelLabel = '文章';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('基本信息')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('标题')
                        ->required()
                        ->maxLength(200),
                    Forms\Components\TextInput::make('slug')
                        ->label('别名')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                    Forms\Components\Textarea::make('summary')
                        ->label('摘要')
                        ->rows(3)
                        ->maxLength(500),
                ])->columns(2),

            Forms\Components\Section::make('内容与分类')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->label('内容')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('category_id')
                        ->label('分类')
                        ->relationship('category', 'name')
                        ->searchable(),
                    Forms\Components\Toggle::make('is_premium')
                        ->label('付费内容'),
                    Forms\Components\Toggle::make('is_published')
                        ->label('已发布'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('标题')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分类'),
                Tables\Columns\IconColumn::make('is_premium')
                    ->label('付费')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('发布')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
