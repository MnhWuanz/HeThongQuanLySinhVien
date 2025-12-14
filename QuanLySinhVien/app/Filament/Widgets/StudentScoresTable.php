<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StudentScoresTable extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Bảng điểm của bạn')
            ->query(
                $this->getTableQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('subject.subject_id')
                    ->label('Mã môn')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Tên môn học')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('subject.credit')
                    ->label('Tín chỉ')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cc')
                    ->label('CC')
                    ->numeric(decimalPlaces: 2)
                    ->alignCenter()
                    ->default('-'),
                Tables\Columns\TextColumn::make('gk')
                    ->label('GK')
                    ->numeric(decimalPlaces: 2)
                    ->alignCenter()
                    ->default('-'),
                Tables\Columns\TextColumn::make('ck')
                    ->label('CK')
                    ->numeric(decimalPlaces: 2)
                    ->alignCenter()
                    ->default('-'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tổng kết')
                    ->numeric(decimalPlaces: 2)
                    ->alignCenter()
                    ->weight('bold')
                    ->color(fn ($state) => match(true) {
                        $state >= 8.5 => 'success',
                        $state >= 7.0 => 'info',
                        $state >= 5.5 => 'warning',
                        $state >= 4.0 => 'warning',
                        default => 'danger',
                    })
                    ->badge()
                    ->default('-'),
            ])
            ->defaultSort('subject.name', 'asc')
            ->paginated([10, 25, 50]);
    }

    protected function getTableQuery(): ?Builder
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->student) {
            return Score::query()->whereRaw('1 = 0'); // Empty query
        }

        return Score::query()
            ->where('student_id', $user->student->student_id)
            ->with(['subject']);
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('Student');
    }
}
