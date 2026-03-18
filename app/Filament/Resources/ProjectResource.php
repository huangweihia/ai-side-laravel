<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('项目信息')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('full_name')
                            ->label('完整名称')
                            ->maxLength(150),
                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->url()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('描述')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('统计数据')
                    ->schema([
                        Forms\Components\TextInput::make('language')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('stars')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('forks')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('score')
                            ->numeric()
                            ->default(0)
                            ->step(0.01)
                            ->maxDigits(5)
                            ->decimalPlaces(2),
                    ])->columns(4),

                Forms\Components\Section::make('分析')
                    ->schema([
                        Forms\Components\Select::make('difficulty')
                            ->options([
                                'easy' => '简单',
                                'medium' => '中等',
                                'hard' => '困难',
                            ])
                            ->default('medium'),
                        Forms\Components\Textarea::make('monetization')
                            ->label('变现方式')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\SpatieTagsInput::make('tags')
                            ->label('标签'),
                    ])->columns(2),

                Forms\Components\Section::make('设置')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('推荐项目'),
                        Forms\Components\DateTimePicker::make('collected_at')
                            ->label('收录时间'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('language')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('stars')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-m-star')
                    ->color('warning'),
                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->sortable()
                    ->color('success'),
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->colors([
                        'easy' => 'success',
                        'medium' => 'warning',
                        'hard' => 'danger',
                    ])
                    ->formatStateUsing(fn ($state) => [
                        'easy' => '简单',
                        'medium' => '中等',
                        'hard' => '困难',
                    ][$state] ?? $state),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('推荐')
                    ->boolean(),
                Tables\Columns\TextColumn::make('collected_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'easy' => '简单',
                        'medium' => '中等',
                        'hard' => '困难',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('推荐项目'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('open')
                    ->url(fn (Project $record): string => $record->url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-top-right-on-square'),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
