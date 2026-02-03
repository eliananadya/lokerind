<?php

namespace App\Filament\Resources\PrefferedDaysResource\Pages;

use App\Filament\Resources\PrefferedDaysResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrefferedDays extends ListRecords
{
    protected static string $resource = PrefferedDaysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
