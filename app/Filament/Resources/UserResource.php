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
    protected static ?string $navigationLabel = '用户管理';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('基本信息')->schema([
                Forms\Components\TextInput::make('name')->label('姓名')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->label('邮箱')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')->label('密码')->password()->dehydrateStateUsing(fn ($state) => Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create')->maxLength(255),
            ])->columns(2),
            Forms\Components\Section::make('角色与会员')->schema([
                Forms\Components\Select::make('role')->label('角色')->options(['user' => '普通用户', 'vip' => 'VIP 会员', 'admin' => '管理员'])->default('user')->required(),
                Forms\Components\DateTimePicker::make('subscription_ends_at')->label('会员到期时间'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
            Tables\Columns\TextColumn::make('name')->label('姓名')->searchable(),
            Tables\Columns\TextColumn::make('email')->label('邮箱')->searchable(),
            Tables\Columns\TextColumn::make('role')->label('角色')->badge()->color(fn (string $state): string => match ($state) { 'admin' => 'danger', 'vip' => 'success', default => 'gray', }),
            Tables\Columns\TextColumn::make('subscription_ends_at')->label('会员到期')->dateTime('Y-m-d')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('创建时间')->dateTime('Y-m-d H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->filters([
            Tables\Filters\SelectFilter::make('role')->label('角色筛选')->options(['user' => '普通用户', 'vip' => 'VIP 会员', 'admin' => '管理员']),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListUsers::route('/'), 'create' => Pages\CreateUser::route('/create'), 'edit' => Pages\EditUser::route('/{record}/edit'),];
    }
}
