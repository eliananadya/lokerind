<?php

namespace App\Filament\Resources\JobPostingBenefitResource\Pages;

use App\Filament\Resources\JobPostingBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobPostingBenefit extends EditRecord
{
    protected static string $resource = JobPostingBenefitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
