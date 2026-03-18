<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本信息')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('avatar')
                            ->image()
                            ->directory('avatars'),
                    ])->columns(2),

                Forms\Components\Section::make('角色与权限')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->options([
                                'user' => '普通用户',
                                'vip' => 'VIP 会员',
                                'admin' => '管理员',
                            ])
                            ->default('user')
                            ->required(),
                        Forms\Components\DateTimePicker::make('subscription_ends_at')
                            ->label('会员到期时间'),
                    ])->columns(2),

                Forms\Components\Section::make('登录信息')
                    ->schema([
                        Forms\Components\DateTimePicker::make('last_login_at')
                            ->disabled(),
                        Forms\Components\TextInput::make('last_login_ip')
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
                    ->sortable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'user' => 'gray',
                        'vip' => 'warning',
                        'admin' => 'success',
                    ]),
                Tables\Columns\TextColumn::make('subscription_ends_at')
                    ->dateTime('Y-m-d H:i')
                    ->label('会员到期'),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->dateTime('Y-m-d H:i')
                    ->label('最后登录'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => '普通用户',
                        'vip' => 'VIP 会员',
                        'admin' => '管理员',
                    ]),
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
