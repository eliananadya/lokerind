<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Lamaran berhasil dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default applied_at jika belum diisi
        if (!isset($data['applied_at'])) {
            $data['applied_at'] = now();
        }

        // Set default status jika belum diisi
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        return $data;
    }
}
