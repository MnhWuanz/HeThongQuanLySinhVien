<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Exports\StudentsWithScoresExport;
use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Xuất Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return Excel::download(
                        new StudentsWithScoresExport,
                        'danh-sach-sinh-vien-kem-diem-' . now()->format('Y-m-d-His') . '.xlsx'
                    );
                })
                ->requiresConfirmation(false),
            Actions\CreateAction::make(),
        ];
    }
}
