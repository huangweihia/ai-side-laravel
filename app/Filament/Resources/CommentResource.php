<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';
    protected static ?string $navigationLabel = '评论管理';
    protected static ?string $modelLabel = '评论';
    protected static ?int $navigationSort = 8;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('评论信息')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('用户')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required()
                        ->disabled(),
                    Forms\Components\Textarea::make('content')
                        ->label('内容')
                        ->rows(4)
                        ->required(),
                    Forms\Components\TextInput::make('like_count')
                        ->label('点赞数')
                        ->numeric()
                        ->disabled(),
                    Forms\Components\Toggle::make('is_hidden')
                        ->label('隐藏'),
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('用户')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commentable_type')
                    ->label('对象')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'App\\Models\\Article' => '文章',
                        'App\\Models\\Project' => '项目',
                        'App\\Models\\KnowledgeBase' => '知识库',
                        'App\\Models\\JobListing' => '资料',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('target_title')
                    ->label('目标内容')
                    ->getStateUsing(function (Comment $record): string {
                        $target = $record->commentable;
                        if (!$target) {
                            return '内容已删除';
                        }

                        return (string) ($target->title ?? $target->name ?? '未命名内容');
                    })
                    ->limit(24),
                Tables\Columns\TextColumn::make('content')
                    ->label('评论内容')
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('like_count')
                    ->label('赞')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_hidden')
                    ->label('隐藏')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('commentable_type')
                    ->label('对象类型')
                    ->options([
                        'App\\Models\\Article' => '文章',
                        'App\\Models\\Project' => '项目',
                    ]),
                Tables\Filters\TernaryFilter::make('is_hidden')
                    ->label('隐藏状态')
                    ->trueLabel('已隐藏')
                    ->falseLabel('显示中'),
                Tables\Filters\Filter::make('recent')
                    ->label('近7天')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('toggle_hidden')
                    ->label(fn (Comment $record): string => $record->is_hidden ? '取消隐藏' : '隐藏')
                    ->icon(fn (Comment $record): string => $record->is_hidden ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn (Comment $record): string => $record->is_hidden ? 'success' : 'warning')
                    ->requiresConfirmation()
                    ->action(function (Comment $record): void {
                        $record->update(['is_hidden' => !$record->is_hidden]);
                    }),
                Action::make('hide_user_comments')
                    ->label('隐藏该用户全部评论')
                    ->icon('heroicon-o-user-minus')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('将隐藏该用户的所有评论，包含文章/项目/资料/知识库评论')
                    ->action(function (Comment $record): void {
                        Comment::query()
                            ->where('user_id', $record->user_id)
                            ->update(['is_hidden' => true]);
                    }),
                Action::make('delete_user_comments')
                    ->label('删除该用户全部评论')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('将永久删除该用户的所有评论，请谨慎操作')
                    ->action(function (Comment $record): void {
                        Comment::query()
                            ->where('user_id', $record->user_id)
                            ->delete();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('hide_selected')
                        ->label('批量隐藏')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => Comment::whereIn('id', $records->pluck('id'))->update(['is_hidden' => true])),
                    BulkAction::make('show_selected')
                        ->label('批量取消隐藏')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => Comment::whereIn('id', $records->pluck('id'))->update(['is_hidden' => false])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
