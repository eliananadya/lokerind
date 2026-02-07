<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Applications extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'status',
        'message',
        'applied_at',
        'rating_candidates',
        'rating_company',
        'review_candidate',
        'review_company',
        'candidates_id',
        'job_posting_id',
        'invited_by_company',
        'invited_at',
        'withdrawn_at',
        'withdraw_reason',
    ];

    protected $casts = [
        'invited_by_company' => 'boolean',
        'invited_at' => 'datetime',
        'applied_at' => 'date',
        'withdrawn_at' => 'datetime',
        'rating_candidates' => 'integer',
        'rating_company' => 'integer',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SELECTION = 'selection';
    const STATUS_INVITED = 'invited';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';
    const STATUS_FINISHED = 'finished';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SELECTION => 'Dalam Seleksi',
            self::STATUS_INVITED => 'Diundang',
            self::STATUS_ACCEPTED => 'Diterima',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_WITHDRAWN => 'Dibatalkan',
            self::STATUS_FINISHED => 'Selesai',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_SELECTION => 'info',
            self::STATUS_INVITED => 'primary',
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_WITHDRAWN => 'secondary',
            self::STATUS_FINISHED => 'dark',
            default => 'secondary',
        };
    }

    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_SELECTION,
            self::STATUS_INVITED,
        ]);
    }

    public function isFinal(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_WITHDRAWN,
            self::STATUS_FINISHED,
        ]);
    }

    public function canBeWithdrawn(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_SELECTION,
            self::STATUS_INVITED,
        ]);
    }

    public function canBeRated(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInSelection(): bool
    {
        return $this->status === self::STATUS_SELECTION;
    }

    public function isInvited(): bool
    {
        return $this->status === self::STATUS_INVITED;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isWithdrawn(): bool
    {
        return $this->status === self::STATUS_WITHDRAWN;
    }

    public function isFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id', 'id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id');
    }

    public function job()
    {
        return $this->jobPosting();
    }

    public function feedbacks()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_applications', 'application_id', 'feedback_id')
            ->withPivot('given_by')
            ->withTimestamps();
    }

    public function feedbackApplications()
    {
        return $this->hasMany(FeedbackApplication::class, 'application_id');
    }

    public function historyPoints()
    {
        return $this->hasMany(HistoryPoint::class, 'application_id');
    }

    public function reports()
    {
        return $this->hasMany(Reports::class, 'application_id');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_SELECTION,
            self::STATUS_INVITED,
        ]);
    }

    public function scopeFinal($query)
    {
        return $query->whereIn('status', [
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_WITHDRAWN,
            self::STATUS_FINISHED,
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInSelection($query)
    {
        return $query->where('status', self::STATUS_SELECTION);
    }

    public function scopeInvited($query)
    {
        return $query->where('status', self::STATUS_INVITED);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeWithdrawn($query)
    {
        return $query->where('status', self::STATUS_WITHDRAWN);
    }

    public function scopeFinished($query)
    {
        return $query->where('status', self::STATUS_FINISHED);
    }

    public function scopeForCandidate($query, int $candidateId)
    {
        return $query->where('candidates_id', $candidateId);
    }

    public function scopeForJob($query, int $jobPostingId)
    {
        return $query->where('job_posting_id', $jobPostingId);
    }

    public function scopeInvitedByCompany($query)
    {
        return $query->where('invited_by_company', true);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('applied_at', '>=', now()->subDays($days));
    }

    public function updateStatus(string $newStatus, ?string $reason = null): bool
    {
        $oldStatus = $this->status;

        if (!in_array($newStatus, array_keys(self::getStatuses()))) {
            Log::error('Invalid status', [
                'application_id' => $this->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            return false;
        }

        $this->status = $newStatus;

        if ($newStatus === self::STATUS_WITHDRAWN) {
            $this->withdrawn_at = now();
            if ($reason) {
                $this->withdraw_reason = $reason;
            }
        }

        if ($newStatus === self::STATUS_INVITED) {
            $this->invited_at = now();
        }

        $saved = $this->save();

        if ($saved) {
            Log::info('Application status updated', [
                'application_id' => $this->id,
                'candidate_id' => $this->candidates_id,
                'job_id' => $this->job_posting_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason
            ]);
        }

        return $saved;
    }

    public function withdraw(string $reason): bool
    {
        if (!$this->canBeWithdrawn()) {
            Log::warning('Cannot withdraw application', [
                'application_id' => $this->id,
                'current_status' => $this->status
            ]);
            return false;
        }

        return $this->updateStatus(self::STATUS_WITHDRAWN, $reason);
    }

    public function accept(): bool
    {
        return $this->updateStatus(self::STATUS_ACCEPTED);
    }

    public function reject(?string $reason = null): bool
    {
        return $this->updateStatus(self::STATUS_REJECTED, $reason);
    }

    public function moveToSelection(): bool
    {
        return $this->updateStatus(self::STATUS_SELECTION);
    }

    public function invite(): bool
    {
        $this->invited_by_company = true;
        return $this->updateStatus(self::STATUS_INVITED);
    }

    public function finish(): bool
    {
        return $this->updateStatus(self::STATUS_FINISHED);
    }

    public function rateCandidate(int $rating, ?string $review = null): bool
    {
        if (!$this->canBeRated()) {
            Log::warning('Cannot rate candidate - application not finished', [
                'application_id' => $this->id,
                'current_status' => $this->status
            ]);
            return false;
        }

        if ($rating < 1 || $rating > 5) {
            Log::error('Invalid rating value', [
                'application_id' => $this->id,
                'rating' => $rating
            ]);
            return false;
        }

        $this->rating_candidates = $rating;
        if ($review) {
            $this->review_candidate = $review;
        }

        $saved = $this->save();

        if ($saved) {
            Log::info('Candidate rated', [
                'application_id' => $this->id,
                'rating' => $rating
            ]);
        }

        return $saved;
    }

    public function rateCompany(int $rating, ?string $review = null): bool
    {
        if (!$this->canBeRated()) {
            Log::warning('Cannot rate company - application not finished', [
                'application_id' => $this->id,
                'current_status' => $this->status
            ]);
            return false;
        }

        if ($rating < 1 || $rating > 5) {
            Log::error('Invalid rating value', [
                'application_id' => $this->id,
                'rating' => $rating
            ]);
            return false;
        }

        $this->rating_company = $rating;
        if ($review) {
            $this->review_company = $review;
        }

        $saved = $this->save();

        if ($saved) {
            Log::info('Company rated', [
                'application_id' => $this->id,
                'rating' => $rating
            ]);
        }

        return $saved;
    }

    public function hasRatedCompany(): bool
    {
        return !is_null($this->rating_company);
    }

    public function hasRatedCandidate(): bool
    {
        return !is_null($this->rating_candidates);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (!$application->applied_at) {
                $application->applied_at = now();
            }
        });

        static::created(function ($application) {
            Log::info('New application created', [
                'application_id' => $application->id,
                'candidate_id' => $application->candidates_id,
                'job_id' => $application->job_posting_id,
                'status' => $application->status
            ]);
        });

        static::updated(function ($application) {
            if ($application->isDirty('status')) {
                Log::info('Application status changed', [
                    'application_id' => $application->id,
                    'old_status' => $application->getOriginal('status'),
                    'new_status' => $application->status
                ]);
            }
        });
        static::retrieved(function ($application) {
            if ($application->status === 'Accepted') {
                $application->checkAndAutoFinish();
            }
        });
    }
    public function checkAndAutoFinish()
    {
        // Load relasi jika belum
        if (!$this->relationLoaded('jobPosting')) {
            $this->load('jobPosting.jobDatess');
        }

        $jobPosting = $this->jobPosting;

        // Cek apakah ada jadwal kerja
        if (!$jobPosting || !$jobPosting->jobDatess || $jobPosting->jobDatess->isEmpty()) {
            return;
        }

        // Ambil tanggal terakhir
        $lastWorkDate = $jobPosting->jobDatess()->orderBy('date', 'desc')->first();

        if (!$lastWorkDate) {
            return;
        }

        // Cek apakah hari ini sudah melewati tanggal terakhir
        if (now()->isAfter($lastWorkDate->date)) {
            // Update tanpa trigger event lagi
            $this->updateQuietly(['status' => 'Finished']);

            Log::info('Application auto-finished', [
                'application_id' => $this->id,
                'candidate_id' => $this->candidates_id,
                'job_posting_id' => $this->job_posting_id,
                'last_work_date' => $lastWorkDate->date,
                'finished_at' => now()
            ]);
        }
    }
}
