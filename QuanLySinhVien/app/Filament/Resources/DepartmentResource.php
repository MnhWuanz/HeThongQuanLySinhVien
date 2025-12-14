<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Khoa';

    protected static ?string $modelLabel = 'Khoa';

    protected static ?string $pluralModelLabel = 'Khoa';

    protected static ?string $navigationGroup = 'Quản lý học vụ';

    public static function canCreate(): bool
    {
        // Chỉ Super_Admin mới có quyền tạo
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function canEdit($record): bool
    {
        // Chỉ Super_Admin mới có quyền sửa
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        // Chỉ Super_Admin mới có quyền xóa
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        // Chỉ Super_Admin mới có quyền xóa nhiều
        return auth()->user()?->hasRole('Super_Admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('department_id')
                    ->label('Mã khoa')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Mã khoa đã tồn tại.',
                    ])
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditDepartment)
                    ->dehydrated(),
                Forms\Components\TextInput::make('name')
                    ->label('Tên khoa')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department_id')
                    ->label('Mã khoa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên khoa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
