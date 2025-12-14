<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Quản lý Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Quản lý người dùng';

    public static function shouldRegisterNavigation(): bool
    {
        // Chỉ Super_Admin mới thấy menu quản lý user
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function canViewAny(): bool
    {
        // Chỉ Super_Admin mới có quyền xem
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin User')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email đã xác thực lúc')
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Mật khẩu')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(191)
                            ->helperText('Để trống nếu không muốn thay đổi mật khẩu'),
                    ]),

                Forms\Components\Section::make('Phân quyền')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Vai trò')
                            ->options([
                                'Super_Admin' => 'Super Admin',
                                'Teacher' => 'Giảng viên',
                                'Student' => 'Sinh viên',
                            ])
                            ->required()
                            ->default('Student')
                            ->helperText('Chọn vai trò cho user này'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Vai trò')
                    ->badge()
                    ->colors([
                        'danger' => 'Super_Admin',
                        'warning' => 'Teacher',
                        'success' => 'Student',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Super_Admin' => 'Super Admin',
                        'Teacher' => 'Giảng viên',
                        'Student' => 'Sinh viên',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Đã xác thực')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Lọc theo vai trò')
                    ->options([
                        'Super_Admin' => 'Super Admin',
                        'Teacher' => 'Giảng viên',
                        'Student' => 'Sinh viên',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
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
