<?php

namespace App\Filament\Resources\TypeJobsResource\Pages;

use App\Filament\Resources\TypeJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeJobs extends EditRecord
{
    protected static string $resource = TypeJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
