<?php

namespace App\Filament\Resources\SaveJobsResource\Pages;

use App\Filament\Resources\SaveJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaveJobs extends ListRecords
{
    protected static string $resource = SaveJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
