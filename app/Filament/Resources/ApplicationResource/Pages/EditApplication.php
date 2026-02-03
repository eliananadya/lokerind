<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->color('info'),

            Actions\Action::make('approve')
                ->label('Terima')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'approved']);

                    Notification::make()
                        ->title('Lamaran Diterima')
                        ->success()
                        ->send();

                    return redirect()->to(ApplicationResource::getUrl('index'));
                })
                ->visible(fn(): bool => in_array($this->record->status, ['pending', 'reviewed'])),

            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'rejected']);

                    Notification::make()
                        ->title('Lamaran Ditolak')
                        ->warning()
                        ->send();

                    return redirect()->to(ApplicationResource::getUrl('index'));
                })
                ->visible(fn(): bool => in_array($this->record->status, ['pending', 'reviewed'])),

            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Lamaran berhasil diperbarui';
    }
}
