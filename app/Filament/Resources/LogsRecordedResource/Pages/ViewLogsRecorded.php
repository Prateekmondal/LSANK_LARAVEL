<?php

namespace App\Filament\Resources\LogsRecordedResource\Pages;

use App\Filament\Resources\LogsRecordedResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLogsRecorded extends ViewRecord
{
    protected static string $resource = LogsRecordedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
