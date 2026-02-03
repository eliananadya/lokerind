<?php

namespace App\Filament\Resources\CandidateSkillResource\Pages;

use App\Filament\Resources\CandidateSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCandidateSkill extends EditRecord
{
    protected static string $resource = CandidateSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
