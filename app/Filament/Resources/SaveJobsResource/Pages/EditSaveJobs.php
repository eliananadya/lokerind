<?php

namespace App\Filament\Resources\SaveJobsResource\Pages;

use App\Filament\Resources\SaveJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaveJobs extends EditRecord
{
    protected static string $resource = SaveJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
