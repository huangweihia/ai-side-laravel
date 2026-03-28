<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobListingResource\Pages;
use App\Models\JobListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class JobListingResource extends Resource
{
    protected static ?string $model = JobListing::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = '职位管理';
    protected static ?string $modelLabel = '职位';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('职位信息')
                ->schema([
                    Forms\Components\TextInput::make('title')->label('职位名称')->required()->maxLength(200),
                    Forms\Components\TextInput::make('company')->label('公司名称')->required()->maxLength(200),
                    Forms\Components\TextInput::make('salary')->label('薪资')->maxLength(100),
                    Forms\Components\TextInput::make('location')->label('工作地点')->default('杭州')->maxLength(100),
                ])->columns(2),

            Forms\Components\Section::make('详细信息')
                ->schema([
                    Forms\Components\Textarea::make('description')->label('职位描述')->rows(5)->columnSpanFull(),
                    Forms\Components\TextInput::make('url')->label('职位链接')->url()->maxLength(500),
                    Forms\Components\Select::make('source')
                        ->label('来源')
                        ->options([
                            'boss' => 'BOSS 直聘',
                            'zhipin' => '智联招聘',
                            'lagou' => '拉勾网',
                        ])
                        ->default('boss'),
                    Forms\Components\DateTimePicker::make('published_at')->label('发布时间'),
                    Forms\Components\Toggle::make('is_sent')->label('已发送邮件')->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('title')->label('职位')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('company')->label('公司')->searchable(),
                Tables\Columns\TextColumn::make('comments_count')
                    ->label('评论数')
                    ->counts('comments')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('salary')->label('薪资'),
                Tables\Columns\TextColumn::make('location')->label('地点'),
                Tables\Columns\IconColumn::make('is_sent')->label('已发送')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->label('发布时间')->dateTime('Y-m-d H:i')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('创建时间')->dateTime('Y-m-d H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->label('来源')
                    ->options([
                        'boss' => 'BOSS 直聘',
                        'zhipin' => '智联招聘',
                        'lagou' => '拉勾网',
                    ]),
                Tables\Filters\TernaryFilter::make('is_sent')
                    ->label('邮件发送状态')
                    ->trueLabel('已发送')
                    ->falseLabel('未发送'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('viewComments')
                    ->label('查看评论')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->url(fn (JobListing $record): string => CommentResource::getUrl('index', [
                        'tableFilters' => [
                            'commentable_type' => ['value' => JobListing::class],
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
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobListings::route('/'),
            'create' => Pages\CreateJobListing::route('/create'),
            'edit' => Pages\EditJobListing::route('/{record}/edit'),
        ];
    }
}
