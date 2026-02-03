<?php

namespace App\Filament\Resources\JobPostingSkillResource\Pages;

use App\Filament\Resources\JobPostingSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobPostingSkill extends EditRecord
{
    protected static string $resource = JobPostingSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
