<?php

namespace App\Filament\Resources\FeedbackApplicationResource\Pages;

use App\Filament\Resources\FeedbackApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedbackApplication extends EditRecord
{
    protected static string $resource = FeedbackApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
