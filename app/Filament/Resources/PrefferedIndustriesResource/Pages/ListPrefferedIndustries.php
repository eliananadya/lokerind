<?php

namespace App\Filament\Resources\PrefferedIndustriesResource\Pages;

use App\Filament\Resources\PrefferedIndustriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrefferedIndustries extends ListRecords
{
    protected static string $resource = PrefferedIndustriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
