<?php

namespace App\Filament\Resources\JobPostingSkillResource\Pages;

use App\Filament\Resources\JobPostingSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobPostingSkills extends ListRecords
{
    protected static string $resource = JobPostingSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
