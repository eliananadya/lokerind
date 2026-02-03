<?php

namespace App\Filament\Resources\JobDatesResource\Pages;

use App\Filament\Resources\JobDatesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobDates extends EditRecord
{
    protected static string $resource = JobDatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
