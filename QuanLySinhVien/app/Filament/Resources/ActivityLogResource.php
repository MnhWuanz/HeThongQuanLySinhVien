<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Lịch sử thay đổi';

    protected static ?string $modelLabel = 'Lịch sử';

    protected static ?string $pluralModelLabel = 'Lịch sử thay đổi';

    protected static ?string $navigationGroup = 'Hệ thống';

    protected static ?int $navigationSort = 99;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin chung')
                    ->schema([
                        Forms\Components\TextInput::make('log_name')
                            ->label('Loại log'),
                        Forms\Components\TextInput::make('description')
                            ->label('Mô tả'),
                        Forms\Components\TextInput::make('event')
                            ->label('Sự kiện'),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Thời gian')
                            ->formatStateUsing(fn($state) => $state instanceof \Carbon\Carbon ? $state->format('d/m/Y H:i:s') : $state),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Đối tượng')
                    ->schema([
                        Forms\Components\TextInput::make('subject_type')
                            ->label('Model')
                            ->formatStateUsing(fn($state) => $state ? class_basename($state) : '—'),
                        Forms\Components\TextInput::make('subject_id')
                            ->label('ID'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Người thực hiện')
                    ->schema([
                        Forms\Components\TextInput::make('causer_name')
                            ->label('Tên')
                            ->formatStateUsing(fn($record) => $record->causer?->name ?? 'Hệ thống'),
                        Forms\Components\TextInput::make('causer_email')
                            ->label('Email')
                            ->formatStateUsing(fn($record) => $record->causer?->email ?? '—'),
                        Forms\Components\TextInput::make('causer_type')
                            ->label('Loại')
                            ->formatStateUsing(fn($state) => $state ? class_basename($state) : '—'),
                        Forms\Components\TextInput::make('causer_id')
                            ->label('ID')
                            ->formatStateUsing(fn($record) => $record->causer_id ?? '—'),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('Dữ liệu thay đổi')
                    ->schema([
                        Forms\Components\Placeholder::make('attributes')
                            ->label('Giá trị mới')
                            ->content(function ($record) {
                                $properties = $record->properties ?? [];
                                $attributes = $properties['attributes'] ?? [];
                                return $attributes ? view('filament.components.json-display', ['data' => $attributes]) : '—';
                            })
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('old')
                            ->label('Giá trị cũ')
                            ->content(function ($record) {
                                $properties = $record->properties ?? [];
                                $old = $properties['old'] ?? [];
                                return $old ? view('filament.components.json-display', ['data' => $old]) : '—';
                            })
                            ->columnSpanFull()
                            ->visible(fn($record) => !empty($record->properties['old'] ?? [])),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Loại')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Hành động')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Đối tượng')
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Người thực hiện')
                    ->searchable()
                    ->sortable()
                    ->default('Hệ thống'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Loại log')
                    ->options([
                        'default' => 'Mặc định',
                        'user' => 'Người dùng',
                        'student' => 'Sinh viên',
                        'score' => 'Điểm',
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Đối tượng')
                    ->options([
                        'App\\Models\\User' => 'User',
                        'App\\Models\\Student' => 'Student',
                        'App\\Models\\Score' => 'Score',
                        'App\\Models\\Subject' => 'Subject',
                        'App\\Models\\ClassModel' => 'Class',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(25)
            ->poll('30s');
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
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
