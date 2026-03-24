<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = '用户管理';
    protected static ?string $modelLabel = '用户';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本信息')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('姓名')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('邮箱')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->label('密码')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('角色与会员')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('角色')
                            ->options([
                                'user' => '普通用户',
                                'vip' => 'VIP 会员',
                                'admin' => '管理员',
                            ])
                            ->default('user')
                            ->required(),
                        Forms\Components\DateTimePicker::make('subscription_ends_at')
                            ->label('会员到期时间')
                            ->helperText('留空表示非 VIP 用户'),
                        Forms\Components\DateTimePicker::make('last_login_at')
                            ->label('最后登录时间')
                            ->disabled(),
                        Forms\Components\TextInput::make('last_login_ip')
                            ->label('最后登录 IP')
                            ->disabled()
                            ->maxLength(45),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('姓名')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('角色')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'vip' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => '👑 管理员',
                        'vip' => '⭐ VIP',
                        default => '👤 普通用户',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription_ends_at')
                    ->label('会员到期')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->placeholder('非 VIP'),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('最后登录')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('注册时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('角色筛选')
                    ->options([
                        'user' => '普通用户',
                        'vip' => 'VIP 会员',
                        'admin' => '管理员',
                    ]),
                Tables\Filters\TernaryFilter::make('vip')
                    ->label('VIP 状态')
                    ->trueLabel('VIP 用户')
                    ->falseLabel('非 VIP')
                    ->query(fn ($query, $state) => match ($state) {
                        true => $query->where('role', 'vip'),
                        false => $query->where('role', '!=', 'vip'),
                        default => $query,
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('extendVip')
                    ->label('延长 VIP')
                    ->icon('heroicon-m-star')
                    ->color('success')
                    ->visible(fn ($record) => $record->role === 'vip')
                    ->form([
                        Forms\Components\TextInput::make('days')
                            ->label('延长天数')
                            ->numeric()
                            ->default(30)
                            ->required(),
                    ])
                    ->action(fn ($record, $data) => $record->update([
                        'subscription_ends_at' => $record->subscription_ends_at?->addDays($data['days']) ?? now()->addDays($data['days']),
                    ]))
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('makeVip')
                        ->label('设为 VIP')
                        ->icon('heroicon-m-star')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['role' => 'vip']))),
                    Tables\Actions\BulkAction::make('makeUser')
                        ->label('设为普通用户')
                        ->icon('heroicon-m-user')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['role' => 'user']))),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
