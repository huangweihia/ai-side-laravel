<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Jobs\ProcessAsyncTaskJob;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = '文章管理';
    protected static ?string $modelLabel = '文章';
    protected static ?int $navigationSort = 3;

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
                    Forms\Components\Toggle::make('is_vip')
                        ->label('是否 VIP'),
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
                Tables\Columns\TextColumn::make('comments_count')
                    ->label('评论数')
                    ->counts('comments')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('is_vip')
                    ->label('是否VIP')
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
                Action::make('viewComments')
                    ->label('查看评论')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->url(fn (Article $record): string => CommentResource::getUrl('index', [
                        'tableFilters' => [
                            'commentable_type' => ['value' => Article::class],
                            'commentable_id' => ['value' => (string) $record->id],
                        ],
                    ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('fetchArticles')
                    ->label('📥 采集文章')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('采集文章')
                    ->modalDescription('将在后台异步采集文章，完成后会通知你。')
                    ->modalSubmitActionLabel('开始采集')
                    ->action(function () {
                        try {
                            $task = \App\Models\AsyncTask::createTask('文章采集', 'fetch_articles');
                            ProcessAsyncTaskJob::dispatch($task);

                            Notification::make()
                                ->title('✅ 采集任务已启动')
                                ->body('文章采集已在后台执行，可在任务管理查看进度')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('❌ 采集失败')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
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
