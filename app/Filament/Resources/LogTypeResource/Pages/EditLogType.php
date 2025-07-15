<?php

namespace App\Filament\Resources\LogTypeResource\Pages;

use App\Filament\Resources\LogTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogType extends EditRecord
{
    protected static string $resource = LogTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
