<?php

namespace App\Filament\Resources\HistoryPointResource\Pages;

use App\Filament\Resources\HistoryPointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistoryPoint extends EditRecord
{
    protected static string $resource = HistoryPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
