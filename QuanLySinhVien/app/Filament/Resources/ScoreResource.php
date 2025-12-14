<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoreResource\Pages;
use App\Filament\Resources\ScoreResource\RelationManagers;
use App\Models\Score;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScoreResource extends Resource
{
    protected static ?string $model = Score::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Điểm số';

    protected static ?string $modelLabel = 'Điểm số';

    protected static ?string $pluralModelLabel = 'Điểm số';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Sinh viên')
                    ->relationship('student', 'full_name', fn ($query) => $query->orderBy('student_id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->student_id} - {$record->full_name}"),
                Forms\Components\Select::make('subject_id')
                    ->label('Môn học')
                    ->relationship('subject', 'name', fn ($query) => $query->orderBy('subject_id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->subject_id} - {$record->name}"),
                Forms\Components\TextInput::make('cc')
                    ->label('Chuyên cần (CC)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->step(0.01)
                    ->suffix('/10')
                    ->helperText('Điểm chuyên cần (0-10)')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, $get) => $set('total', 
                        round(($get('cc') ?? 0) * 0.1 + ($get('gk') ?? 0) * 0.3 + ($get('ck') ?? 0) * 0.6, 2)
                    )),
                Forms\Components\TextInput::make('gk')
                    ->label('Giữa kỳ (GK)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->step(0.01)
                    ->suffix('/10')
                    ->helperText('Điểm giữa kỳ (0-10)')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, $get) => $set('total', 
                        round(($get('cc') ?? 0) * 0.1 + ($get('gk') ?? 0) * 0.3 + ($get('ck') ?? 0) * 0.6, 2)
                    )),
                Forms\Components\TextInput::make('ck')
                    ->label('Cuối kỳ (CK)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->step(0.01)
                    ->suffix('/10')
                    ->helperText('Điểm cuối kỳ (0-10)')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set, $get) => $set('total', 
                        round(($get('cc') ?? 0) * 0.1 + ($get('gk') ?? 0) * 0.3 + ($get('ck') ?? 0) * 0.6, 2)
                    )),
                Forms\Components\TextInput::make('total')
                    ->label('Tổng kết')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->step(0.01)
                    ->suffix('/10')
                    ->disabled()
                    ->dehydrated()
                    ->default(fn ($get) => round(($get('cc') ?? 0) * 0.1 + ($get('gk') ?? 0) * 0.3 + ($get('ck') ?? 0) * 0.6, 2))
                    ->helperText('Điểm tổng kết (tự động tính: CC*0.1 + GK*0.3 + CK*0.6)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Mã SV')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Tên sinh viên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.subject_id')
                    ->label('Mã môn')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Tên môn học')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cc')
                    ->label('CC')
                    ->numeric(
                        decimalPlaces: 2,
                    )
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('gk')
                    ->label('GK')
                    ->numeric(
                        decimalPlaces: 2,
                    )
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('ck')
                    ->label('CK')
                    ->numeric(
                        decimalPlaces: 2,
                    )
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tổng kết')
                    ->numeric(
                        decimalPlaces: 2,
                    )
                    ->sortable()
                    ->alignCenter()
                    ->color(fn ($record) => match (true) {
                        $record->total >= 8.5 => 'success',
                        $record->total >= 7.0 => 'info',
                        $record->total >= 5.5 => 'warning',
                        default => 'danger',
                    })
                    ->weight('bold'),
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
                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Sinh viên')
                    ->relationship('student', 'full_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('subject_id')
                    ->label('Môn học')
                    ->relationship('subject', 'name')
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
            'index' => Pages\ListScores::route('/'),
            'create' => Pages\CreateScore::route('/create'),
            'edit' => Pages\EditScore::route('/{record}/edit'),
        ];
    }
}
