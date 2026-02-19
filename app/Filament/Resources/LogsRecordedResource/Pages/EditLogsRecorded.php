<?php

namespace App\Filament\Resources\LogsRecordedResource\Pages;

use App\Filament\Resources\LogsRecordedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogsRecorded extends EditRecord
{
    protected static string $resource = LogsRecordedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
