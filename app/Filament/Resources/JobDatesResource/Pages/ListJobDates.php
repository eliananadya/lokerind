<?php

namespace App\Filament\Resources\JobDatesResource\Pages;

use App\Filament\Resources\JobDatesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobDates extends ListRecords
{
    protected static string $resource = JobDatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
