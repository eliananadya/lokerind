<?php

namespace App\Filament\Resources\SubscribesResource\Pages;

use App\Filament\Resources\SubscribesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscribes extends ListRecords
{
    protected static string $resource = SubscribesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
