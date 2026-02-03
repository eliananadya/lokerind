<?php

namespace App\Filament\Resources\SubscribesResource\Pages;

use App\Filament\Resources\SubscribesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscribes extends EditRecord
{
    protected static string $resource = SubscribesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
