<?php

namespace App\Filament\Resources\PrefferedTypeJobsResource\Pages;

use App\Filament\Resources\PrefferedTypeJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrefferedTypeJobs extends EditRecord
{
    protected static string $resource = PrefferedTypeJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
