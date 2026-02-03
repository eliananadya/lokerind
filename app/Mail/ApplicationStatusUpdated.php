<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Applications;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Applications $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->getStatusText($this->newStatus);

        return new Envelope(
            subject: "📢 Status Lamaran Anda: {$statusText} - {$this->application->jobPosting->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application-status-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get status text in Indonesian
     */
    private function getStatusText($status)
    {
        $statusMap = [
            'Applied' => 'Sedang Ditinjau',
            'Reviewed' => 'Sedang Ditinjau',
            'Interview' => 'Undangan Interview',
            'Accepted' => 'Diterima',
            'Rejected' => 'Ditolak',
            'Withdrawn' => 'Dibatalkan',
            'Finished' => 'Selesai',
            'invited' => 'Diundang'
        ];

        return $statusMap[$status] ?? $status;
    }
}
