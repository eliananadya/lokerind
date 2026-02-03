<?php

namespace App\Filament\Resources\TypeJobsResource\Pages;

use App\Filament\Resources\TypeJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeJobs extends ListRecords
{
    protected static string $resource = TypeJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
