<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HistoryPoint extends Model
{
    use HasFactory;

    protected $table = 'history_points';

    /**
     * ✅ FILLABLE ATTRIBUTES
     */
    protected $fillable = [
        'candidates_id',
        'application_id',
        'old_point',
        'new_point',
        'reason',
    ];

    /**
     * ✅ CASTS
     */
    protected $casts = [
        'candidates_id' => 'integer',
        'application_id' => 'integer',
        'old_point' => 'integer',
        'new_point' => 'integer',
    ];

    /**
     * ✅ APPENDS (computed attributes)
     */
    protected $appends = ['point_difference'];

    /**
     * ✅ REASON CONSTANTS
     */
    const REASON_REGISTRATION = 'registration';
    const REASON_APPLICATION = 'application';
    const REASON_ACCEPTED = 'accepted';
    const REASON_REJECTED = 'rejected';
    const REASON_WITHDRAWN = 'withdrawn';
    const REASON_BONUS = 'bonus';
    const REASON_PENALTY = 'penalty';
    const REASON_ADJUSTMENT = 'adjustment';

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * ✅ RELASI: Ke Candidates (Many-to-One)
     */
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }

    /**
     * ✅ RELASI: Ke Applications (Many-to-One) - NULLABLE
     */
    public function application()
    {
        return $this->belongsTo(Applications::class, 'application_id');
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    /**
     * ✅ ACCESSOR: Get point difference
     */
    public function getPointDifferenceAttribute(): int
    {
        return $this->new_point - $this->old_point;
    }

    /**
     * ✅ ACCESSOR: Check if point increased
     */
    public function getIsIncreasedAttribute(): bool
    {
        return $this->new_point > $this->old_point;
    }

    /**
     * ✅ ACCESSOR: Check if point decreased
     */
    public function getIsDecreasedAttribute(): bool
    {
        return $this->new_point < $this->old_point;
    }

    /**
     * ✅ ACCESSOR: Get formatted point difference
     */
    public function getFormattedDifferenceAttribute(): string
    {
        $diff = $this->point_difference;

        if ($diff > 0) {
            return '+' . $diff;
        } elseif ($diff < 0) {
            return (string) $diff;
        } else {
            return '0';
        }
    }

    /**
     * ✅ ACCESSOR: Get reason label
     */
    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            self::REASON_REGISTRATION => 'Registrasi',
            self::REASON_APPLICATION => 'Melamar Pekerjaan',
            self::REASON_ACCEPTED => 'Diterima Kerja',
            self::REASON_REJECTED => 'Ditolak',
            self::REASON_WITHDRAWN => 'Membatalkan Lamaran',
            self::REASON_BONUS => 'Bonus Point',
            self::REASON_PENALTY => 'Penalti',
            self::REASON_ADJUSTMENT => 'Penyesuaian',
            default => $this->reason ?? 'Tidak Diketahui',
        };
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * ✅ SCOPE: For specific candidate
     */
    public function scopeForCandidate($query, int $candidateId)
    {
        return $query->where('candidates_id', $candidateId);
    }

    /**
     * ✅ SCOPE: For specific application
     */
    public function scopeForApplication($query, int $applicationId)
    {
        return $query->where('application_id', $applicationId);
    }

    /**
     * ✅ SCOPE: Without application (registration, bonus, etc)
     */
    public function scopeWithoutApplication($query)
    {
        return $query->whereNull('application_id');
    }

    /**
     * ✅ SCOPE: With application
     */
    public function scopeWithApplication($query)
    {
        return $query->whereNotNull('application_id');
    }

    /**
     * ✅ SCOPE: By reason
     */
    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }

    /**
     * ✅ SCOPE: Point increased
     */
    public function scopeIncreased($query)
    {
        return $query->whereRaw('new_point > old_point');
    }

    /**
     * ✅ SCOPE: Point decreased
     */
    public function scopeDecreased($query)
    {
        return $query->whereRaw('new_point < old_point');
    }

    /**
     * ✅ SCOPE: Recent history
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * ✅ CREATE HISTORY POINT
     *
     * @param int $candidateId
     * @param int $oldPoint
     * @param int $newPoint
     * @param int|null $applicationId
     * @param string|null $reason
     * @return self
     */
    public static function record(
        int $candidateId,
        int $oldPoint,
        int $newPoint,
        ?int $applicationId = null,
        ?string $reason = null
    ): self {
        $history = self::create([
            'candidates_id' => $candidateId,
            'application_id' => $applicationId,
            'old_point' => $oldPoint,
            'new_point' => $newPoint,
            'reason' => $reason,
        ]);

        Log::info('History point recorded', [
            'id' => $history->id,
            'candidate_id' => $candidateId,
            'application_id' => $applicationId,
            'old_point' => $oldPoint,
            'new_point' => $newPoint,
            'difference' => $newPoint - $oldPoint,
            'reason' => $reason
        ]);

        return $history;
    }

    /**
     * ✅ RECORD REGISTRATION POINT
     *
     * @param int $candidateId
     * @param int $point
     * @return self
     */
    public static function recordRegistration(int $candidateId, int $point = 100): self
    {
        return self::record(
            candidateId: $candidateId,
            oldPoint: 0,
            newPoint: $point,
            applicationId: null,
            reason: self::REASON_REGISTRATION
        );
    }

    /**
     * ✅ RECORD APPLICATION POINT
     *
     * @param int $candidateId
     * @param int $applicationId
     * @param int $oldPoint
     * @param int $newPoint
     * @return self
     */
    public static function recordApplication(
        int $candidateId,
        int $applicationId,
        int $oldPoint,
        int $newPoint
    ): self {
        return self::record(
            candidateId: $candidateId,
            oldPoint: $oldPoint,
            newPoint: $newPoint,
            applicationId: $applicationId,
            reason: self::REASON_APPLICATION
        );
    }

    /**
     * ✅ GET HISTORY FOR CANDIDATE
     *
     * @param int $candidateId
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getForCandidate(int $candidateId, ?int $limit = null)
    {
        $query = self::with(['application.jobPosting'])
            ->where('candidates_id', $candidateId)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * ✅ GET TOTAL POINT CHANGE FOR CANDIDATE
     *
     * @param int $candidateId
     * @return int
     */
    public static function getTotalPointChange(int $candidateId): int
    {
        return self::where('candidates_id', $candidateId)
            ->selectRaw('SUM(new_point - old_point) as total_change')
            ->value('total_change') ?? 0;
    }

    /**
     * ✅ GET STATISTICS FOR CANDIDATE
     *
     * @param int $candidateId
     * @return array
     */
    public static function getStatistics(int $candidateId): array
    {
        $histories = self::where('candidates_id', $candidateId)->get();

        $totalIncrease = $histories->filter(fn($h) => $h->new_point > $h->old_point)
            ->sum(fn($h) => $h->new_point - $h->old_point);

        $totalDecrease = $histories->filter(fn($h) => $h->new_point < $h->old_point)
            ->sum(fn($h) => $h->old_point - $h->new_point);

        return [
            'total_records' => $histories->count(),
            'total_increase' => $totalIncrease,
            'total_decrease' => $totalDecrease,
            'net_change' => $totalIncrease - $totalDecrease,
            'average_change' => $histories->count() > 0
                ? $histories->avg(fn($h) => $h->new_point - $h->old_point)
                : 0,
        ];
    }

    // ========================================
    // BOOT METHOD
    // ========================================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($history) {
            Log::info('History point created', [
                'id' => $history->id,
                'candidate_id' => $history->candidates_id,
                'application_id' => $history->application_id,
                'old_point' => $history->old_point,
                'new_point' => $history->new_point,
                'difference' => $history->point_difference,
                'reason' => $history->reason
            ]);
        });
    }
}
