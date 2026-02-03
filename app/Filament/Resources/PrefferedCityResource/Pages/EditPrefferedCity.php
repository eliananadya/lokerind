<?php

namespace App\Filament\Resources\PrefferedCityResource\Pages;

use App\Filament\Resources\PrefferedCityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrefferedCity extends EditRecord
{
    protected static string $resource = PrefferedCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
