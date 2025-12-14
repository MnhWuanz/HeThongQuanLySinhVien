<?php

namespace App\Filament\Student\Pages;

use App\Models\Student;
use App\Models\Score;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Scoreboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.student.pages.scoreboard';

    protected static ?string $navigationLabel = 'Bảng điểm';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Thông tin cá nhân';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public Student $student;

    public function mount(): void
    {
        $user = Auth::user();
        $this->student = Student::where('user_id', $user->id)
            ->with('classRelation')
            ->firstOrFail();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Score::query()
                    ->where('student_id', $this->student->student_id)
                    ->with(['subject'])
            )
            ->columns([
                TextColumn::make('subject.subject_id')
                    ->label('Mã môn học')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Tên môn học')
                    ->searchable(),
                TextColumn::make('subject.credit')
                    ->label('Số tín chỉ'),
                TextColumn::make('cc')
                    ->label('Điểm chuyên cần')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->default('—'),
                TextColumn::make('gk')
                    ->label('Điểm giữa kỳ')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->default('—'),
                TextColumn::make('ck')
                    ->label('Điểm cuối kỳ')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->default('—'),
                TextColumn::make('total')
                    ->label('Điểm tổng kết')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn($state) => $state >= 5 ? 'success' : ($state >= 4 ? 'warning' : 'danger'))
                    ->weight('bold')
                    ->default('—'),
            ])
            ->filters([
                SelectFilter::make('subject_id')
                    ->label('Môn học')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('subject_id', 'asc')
            ->persistSortInSession(false)
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Chưa có điểm số')
            ->emptyStateDescription('Điểm số sẽ được hiển thị tại đây khi giáo viên nhập điểm.');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('refresh')
                ->label('Làm mới')
                ->icon('heroicon-o-arrow-path')
                ->action(fn() => $this->dispatch('$refresh')),
        ];
    }
}

