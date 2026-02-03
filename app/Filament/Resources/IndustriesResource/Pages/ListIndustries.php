<?php

namespace App\Filament\Resources\IndustriesResource\Pages;

use App\Filament\Resources\IndustriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndustries extends ListRecords
{
    protected static string $resource = IndustriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
