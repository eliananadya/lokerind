<?php

namespace App\Filament\Resources\CandidateSkillResource\Pages;

use App\Filament\Resources\CandidateSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCandidateSkills extends ListRecords
{
    protected static string $resource = CandidateSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
