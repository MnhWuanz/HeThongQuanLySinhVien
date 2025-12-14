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
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('subject.name')
                        ->label('Môn học')
                        ->searchable()
                        ->weight('bold')
                        ->size('sm')
                        ->description(fn ($record) => 'Mã: ' . $record->subject->subject_id . ' • ' . $record->subject->credit . ' TC'),
                    
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('cc')
                            ->label('CC')
                            ->badge()
                            ->color('gray')
                            ->default('-')
                            ->formatStateUsing(fn ($state) => 'CC: ' . ($state ?? '-')),
                        
                        Tables\Columns\TextColumn::make('gk')
                            ->label('GK')
                            ->badge()
                            ->color('info')
                            ->default('-')
                            ->formatStateUsing(fn ($state) => 'GK: ' . ($state ?? '-')),
                        
                        Tables\Columns\TextColumn::make('ck')
                            ->label('CK')
                            ->badge()
                            ->color('warning')
                            ->default('-')
                            ->formatStateUsing(fn ($state) => 'CK: ' . ($state ?? '-')),
                        
                        Tables\Columns\TextColumn::make('total')
                            ->label('TK')
                            ->badge()
                            ->size('lg')
                            ->weight('bold')
                            ->color(fn ($state) => match(true) {
                                $state >= 8.5 => 'success',
                                $state >= 7.0 => 'info',
                                $state >= 5.5 => 'warning',
                                $state >= 4.0 => 'warning',
                                default => 'danger',
                            })
                            ->default('-')
                            ->formatStateUsing(fn ($state) => 'TK: ' . ($state ?? '-')),
                    ])->grow(false),
                ])->space(2),
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 1,
            ])
            ->defaultSort('subject.name', 'asc')
            ->paginated([10, 25, 50])
            ->striped()
            ->defaultPaginationPageOption(10);
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
