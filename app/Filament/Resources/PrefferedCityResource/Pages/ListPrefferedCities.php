<?php

namespace App\Filament\Resources\PrefferedCityResource\Pages;

use App\Filament\Resources\PrefferedCityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrefferedCities extends ListRecords
{
    protected static string $resource = PrefferedCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
