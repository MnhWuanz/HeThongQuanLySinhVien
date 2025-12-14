<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Sinh viên';

    protected static ?string $modelLabel = 'Sinh viên';

    protected static ?string $pluralModelLabel = 'Sinh viên';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_id')
                    ->label('Mã sinh viên')
                    ->required()
                    ->maxLength(12)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditStudent)
                    ->dehydrated(),
                Forms\Components\TextInput::make('full_name')
                    ->label('Họ và tên')
                    ->required()
                    ->maxLength(100),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Ngày sinh')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false),
                Forms\Components\FileUpload::make('avatar')
                    ->label('Ảnh đại diện')
                    ->image()
                    ->directory('avatars')
                    ->visibility('public')
                    ->maxSize(2048) // 2MB
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->helperText('Kích thước tối đa: 2MB'),
                Forms\Components\Select::make('class_id')
                    ->label('Lớp')
                    ->relationship('classRelation', 'class_id')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh')
                    ->circular(),
                Tables\Columns\TextColumn::make('student_id')
                    ->label('Mã sinh viên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Họ và tên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Ngày sinh')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRelation.class_id')
                    ->label('Lớp')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_id')
                    ->label('Lớp')
                    ->relationship('classRelation', 'class_id')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([5, 10, 25, 50, 100]);
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
