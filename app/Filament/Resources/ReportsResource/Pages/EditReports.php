<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditReports extends EditRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $status = $this->record->status;

        if ($status === 'approved') {
            if ($this->record->application) {
                $app = $this->record->application;
                $reporter = $this->record->user;

                if ($reporter->isUser() || $reporter->isCandidate()) {
                    // Candidate melaporkan → Hapus rating/review DARI company
                    $feedbackCount = $app->feedbackApplications()->where('given_by', 'company')->count();

                    $app->update([
                        'rating_candidates' => null,
                        'review_candidate' => null,
                    ]);

                    $app->feedbackApplications()->where('given_by', 'company')->delete();

                    Notification::make()
                        ->success()
                        ->title('Laporan Approved')
                        ->body("Rating, review, dan {$feedbackCount} feedback DARI COMPANY telah dihapus.")
                        ->send();
                } elseif ($reporter->isCompany()) {
                    // Company melaporkan → Hapus rating/review DARI candidate
                    $feedbackCount = $app->feedbackApplications()->where('given_by', 'candidate')->count();

                    $app->update([
                        'rating_company' => null,
                        'review_company' => null,
                    ]);

                    $app->feedbackApplications()->where('given_by', 'candidate')->delete();

                    Notification::make()
                        ->success()
                        ->title('Laporan Approved')
                        ->body("Rating, review, dan {$feedbackCount} feedback DARI CANDIDATE telah dihapus.")
                        ->send();
                }
            }
        } elseif ($status === 'rejected') {
            Notification::make()
                ->info()
                ->title('Laporan Rejected')
                ->body('Rating, review, dan feedback tetap ditampilkan.')
                ->send();
        }
    }
}
