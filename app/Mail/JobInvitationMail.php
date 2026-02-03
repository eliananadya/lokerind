<?php

namespace App\Mail;

use App\Models\Candidates;
use App\Models\JobPostings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class JobInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $jobPosting;
    public $company;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Candidates $candidate, JobPostings $jobPosting, $message = null)
    {
        $this->candidate = $candidate;
        $this->jobPosting = $jobPosting;
        $this->company = $jobPosting->company;
        $this->customMessage = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                $this->company->name ?? config('mail.from.name')
            ),
            subject: '🎉 Undangan Melamar Pekerjaan dari ' . ($this->company->name ?? 'Perusahaan'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-invitation',
            with: [
                'candidateName' => $this->candidate->name,
                'jobTitle' => $this->jobPosting->title,
                'companyName' => $this->company->name ?? 'Perusahaan',
                'companyAddress' => $this->company->address ?? '-',
                'jobDescription' => $this->jobPosting->description,
                'jobLocation' => $this->jobPosting->city->name ?? 'Lokasi tidak tersedia',
                'salary' => $this->jobPosting->salary ? 'Rp ' . number_format($this->jobPosting->salary, 0, ',', '.') : 'Negosiasi',
                'customMessage' => $this->customMessage,
                'applyUrl' => route('jobs.show', $this->jobPosting->id),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
