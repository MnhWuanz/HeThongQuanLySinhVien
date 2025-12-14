<?php

namespace App\Filament\Widgets;

use App\Models\ClassModel;
use App\Models\Teacher;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TeacherClassesTable extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Danh sách lớp phụ trách')
            ->query(
                ClassModel::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('class_id')
                    ->label('Mã lớp')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departmentRelation.name')
                    ->label('Khoa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('Số sinh viên')
                    ->counts('students')
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Giáo viên chủ nhiệm')
                    ->default('Chưa có'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Xem chi tiết')
                    ->url(fn (ClassModel $record): string => route('filament.admin.resources.class-models.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('Teacher');
    }
}
