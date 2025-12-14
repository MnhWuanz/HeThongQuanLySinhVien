<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected ?string $heading = 'Lịch sử thay đổi điểm';

    protected ?string $subheading = 'Ghi lại mọi thay đổi về điểm số';

    protected function getHeaderActions(): array
    {
        return [
            // Không có actions
        ];
    }
}
