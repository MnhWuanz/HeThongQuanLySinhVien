<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassModelResource\Pages;
use App\Filament\Resources\ClassModelResource\RelationManagers;
use App\Models\ClassModel;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassModelResource extends Resource
{
    protected static ?string $model = ClassModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Lớp';

    protected static ?string $modelLabel = 'Lớp';

    protected static ?string $pluralModelLabel = 'Lớp';

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
                Forms\Components\TextInput::make('class_id')
                    ->label('Mã lớp')
                    ->required()
                    ->maxLength(15)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditClassModel)
                    ->dehydrated(),
                Forms\Components\Select::make('department')
                    ->label('Khoa')
                    ->relationship('departmentRelation', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('department_id')
                            ->label('Mã khoa')
                            ->required()
                            ->maxLength(10)
                            ->unique(),
                        Forms\Components\TextInput::make('name')
                            ->label('Tên khoa')
                            ->required()
                            ->maxLength(100),
                    ]),
                Forms\Components\Select::make('teacher_id')
                    ->label('Giáo viên chủ nhiệm')
                    ->relationship('teacher', 'full_name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Chọn giảng viên từ danh sách'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('class_id')
                    ->label('Mã lớp')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departmentRelation.name')
                    ->label('Khoa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Giáo viên chủ nhiệm')
                    ->searchable()
                    ->sortable()
                    ->default('Chưa có'),
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
                Tables\Filters\SelectFilter::make('department')
                    ->label('Khoa')
                    ->relationship('departmentRelation', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListClassModels::route('/'),
            'create' => Pages\CreateClassModel::route('/create'),
            'edit' => Pages\EditClassModel::route('/{record}/edit'),
        ];
    }
}
