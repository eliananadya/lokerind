<?php

namespace App\Filament\Resources\PrefferedDaysResource\Pages;

use App\Filament\Resources\PrefferedDaysResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrefferedDays extends EditRecord
{
    protected static string $resource = PrefferedDaysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
