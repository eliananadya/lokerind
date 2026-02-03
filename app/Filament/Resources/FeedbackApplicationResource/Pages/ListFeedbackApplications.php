<?php

namespace App\Filament\Resources\FeedbackApplicationResource\Pages;

use App\Filament\Resources\FeedbackApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackApplications extends ListRecords
{
    protected static string $resource = FeedbackApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
