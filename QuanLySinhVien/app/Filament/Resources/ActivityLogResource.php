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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin')
                    ->schema([
                        Forms\Components\TextInput::make('log_name')
                            ->label('Loại log')
                            ->disabled(),
                        Forms\Components\TextInput::make('description')
                            ->label('Mô tả')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('subject_type')
                            ->label('Model')
                            ->disabled(),
                        Forms\Components\TextInput::make('subject_id')
                            ->label('ID')
                            ->disabled(),
                        Forms\Components\TextInput::make('causer_type')
                            ->label('Người thực hiện')
                            ->disabled(),
                        Forms\Components\TextInput::make('causer_id')
                            ->label('User ID')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Thay đổi')
                    ->schema([
                        Forms\Components\KeyValue::make('properties.attributes')
                            ->label('Giá trị mới')
                            ->disabled(),
                        Forms\Components\KeyValue::make('properties.old')
                            ->label('Giá trị cũ')
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Thời gian')
                    ->schema([
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Thời gian')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Người thực hiện')
                    ->searchable()
                    ->sortable()
                    ->default('Hệ thống'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Hành động')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tạo điểm mới' => 'success',
                        'Cập nhật điểm' => 'warning',
                        'Xóa điểm' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.attributes.student_id')
                    ->label('Mã SV')
                    ->searchable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('properties.attributes.subject_id')
                    ->label('Mã MH')
                    ->searchable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('properties.old.cc')
                    ->label('CC cũ')
                    ->numeric(decimalPlaces: 2)
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.attributes.cc')
                    ->label('CC mới')
                    ->numeric(decimalPlaces: 2)
                    ->default('-'),
                Tables\Columns\TextColumn::make('properties.old.gk')
                    ->label('GK cũ')
                    ->numeric(decimalPlaces: 2)
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.attributes.gk')
                    ->label('GK mới')
                    ->numeric(decimalPlaces: 2)
                    ->default('-'),
                Tables\Columns\TextColumn::make('properties.old.ck')
                    ->label('CK cũ')
                    ->numeric(decimalPlaces: 2)
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.attributes.ck')
                    ->label('CK mới')
                    ->numeric(decimalPlaces: 2)
                    ->default('-'),
                Tables\Columns\TextColumn::make('properties.old.total')
                    ->label('TK cũ')
                    ->numeric(decimalPlaces: 2)
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('properties.attributes.total')
                    ->label('TK mới')
                    ->numeric(decimalPlaces: 2)
                    ->default('-')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Loại log')
                    ->options([
                        'score_changes' => 'Thay đổi điểm',
                    ]),
                Tables\Filters\SelectFilter::make('causer_id')
                    ->label('Người thực hiện')
                    ->relationship('causer', 'name')
                    ->searchable()
                    ->preload(),
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
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Không cho phép bulk delete log
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
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Không cho phép tạo log thủ công
    }

    public static function canDelete($record): bool
    {
        return false; // Không cho phép xóa log
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
