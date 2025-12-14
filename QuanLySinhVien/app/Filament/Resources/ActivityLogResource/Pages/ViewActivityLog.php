<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function resolveRecord($key): Model
    {
        return parent::resolveRecord($key)->load('causer', 'subject');
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
