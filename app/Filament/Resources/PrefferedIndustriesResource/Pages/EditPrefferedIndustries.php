<?php

namespace App\Filament\Resources\PrefferedIndustriesResource\Pages;

use App\Filament\Resources\PrefferedIndustriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrefferedIndustries extends EditRecord
{
    protected static string $resource = PrefferedIndustriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
