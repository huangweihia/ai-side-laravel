<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = '订阅管理';
    protected static ?string $modelLabel = '订阅';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('订阅信息')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('用户')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('邮箱')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('状态')
                            ->options([
                                'active' => '有效',
                                'cancelled' => '已取消',
                                'expired' => '已过期',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\Select::make('plan')
                            ->label('套餐')
                            ->options([
                                'monthly' => '月度会员',
                                'yearly' => '年度会员',
                                'lifetime' => '终身会员',
                            ])
                            ->default('monthly')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('时间设置')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('开始时间')
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('结束时间'),
                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->label('取消时间')
                            ->disabled(),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan')
                    ->label('套餐')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'yearly' => 'success',
                        'lifetime' => 'warning',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'monthly' => '📅 月度',
                        'yearly' => '📆 年度',
                        'lifetime' => '♾️ 终身',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => '✅ 有效',
                        'cancelled' => '❌ 已取消',
                        'expired' => '⏰ 已过期',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('到期时间')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('状态')
                    ->options([
                        'active' => '有效',
                        'cancelled' => '已取消',
                        'expired' => '已过期',
                    ]),
                Tables\Filters\SelectFilter::make('plan')
                    ->label('套餐')
                    ->options([
                        'monthly' => '月度',
                        'yearly' => '年度',
                        'lifetime' => '终身',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('extend')
                    ->label('延长')
                    ->icon('heroicon-m-calendar')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'active')
                    ->form([
                        Forms\Components\TextInput::make('days')
                            ->label('延长天数')
                            ->numeric()
                            ->default(30)
                            ->required(),
                    ])
                    ->action(fn ($record, $data) => $record->update([
                        'ends_at' => $record->ends_at?->addDays($data['days']) ?? now()->addDays($data['days']),
                    ]))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
