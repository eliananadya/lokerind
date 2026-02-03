<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    /**
     * ✅ FILLABLE ATTRIBUTES
     */
    protected $fillable = [
        'name',
        'for',
        'description',
        'is_active',
    ];

    /**
     * ✅ CASTS
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * ✅ CONSTANTS - Feedback Target
     */
    const FOR_CANDIDATE = 'candidate';
    const FOR_COMPANY = 'company';

    /**
     * ✅ GET ALL FEEDBACK TARGETS
     * @return array
     */
    public static function getFeedbackTargets(): array
    {
        return [
            self::FOR_CANDIDATE => 'Kandidat',
            self::FOR_COMPANY => 'Perusahaan',
        ];
    }

    /**
     * ✅ GET TARGET LABEL (Indonesian)
     * @return string
     */
    public function getTargetLabelAttribute(): string
    {
        $targets = self::getFeedbackTargets();
        return $targets[$this->for] ?? $this->for;
    }

    /**
     * ✅ CHECK IF FOR CANDIDATE
     * @return bool
     */
    public function isForCandidate(): bool
    {
        return $this->for === self::FOR_CANDIDATE;
    }

    /**
     * ✅ CHECK IF FOR COMPANY
     * @return bool
     */
    public function isForCompany(): bool
    {
        return $this->for === self::FOR_COMPANY;
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * ✅ RELASI: Applications (many-to-many via pivot)
     */
    public function applications()
    {
        return $this->belongsToMany(
            Applications::class,
            'feedback_applications',
            'feedback_id',
            'application_id'
        )
            ->withPivot('given_by')
            ->withTimestamps();
    }

    /**
     * ✅ RELASI: Feedback Applications (one-to-many)
     */
    public function feedbackApplications()
    {
        return $this->hasMany(FeedbackApplication::class, 'feedback_id');
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * ✅ SCOPE: For candidate
     */
    public function scopeForCandidate($query)
    {
        return $query->where('for', self::FOR_CANDIDATE);
    }

    /**
     * ✅ SCOPE: For company
     */
    public function scopeForCompany($query)
    {
        return $query->where('for', self::FOR_COMPANY);
    }

    /**
     * ✅ SCOPE: Active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * ✅ SCOPE: Inactive only
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * ✅ SCOPE: By target
     */
    public function scopeByTarget($query, string $target)
    {
        return $query->where('for', $target);
    }

    /**
     * ✅ SCOPE: Search by name
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * ✅ GET CANDIDATE FEEDBACKS
     * @param bool $activeOnly
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCandidateFeedbacks(bool $activeOnly = true)
    {
        $query = self::forCandidate();

        if ($activeOnly) {
            $query->active();
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * ✅ GET COMPANY FEEDBACKS
     * @param bool $activeOnly
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCompanyFeedbacks(bool $activeOnly = true)
    {
        $query = self::forCompany();

        if ($activeOnly) {
            $query->active();
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * ✅ CREATE FEEDBACK FOR CANDIDATE
     * @param string $name
     * @param string|null $description
     * @return self
     */
    public static function createForCandidate(string $name, ?string $description = null): self
    {
        $feedback = self::create([
            'name' => $name,
            'for' => self::FOR_CANDIDATE,
            'description' => $description,
            'is_active' => true,
        ]);

        Log::info('Candidate feedback created', [
            'feedback_id' => $feedback->id,
            'name' => $name
        ]);

        return $feedback;
    }

    /**
     * ✅ CREATE FEEDBACK FOR COMPANY
     * @param string $name
     * @param string|null $description
     * @return self
     */
    public static function createForCompany(string $name, ?string $description = null): self
    {
        $feedback = self::create([
            'name' => $name,
            'for' => self::FOR_COMPANY,
            'description' => $description,
            'is_active' => true,
        ]);

        Log::info('Company feedback created', [
            'feedback_id' => $feedback->id,
            'name' => $name
        ]);

        return $feedback;
    }

    /**
     * ✅ TOGGLE ACTIVE STATUS
     * @return bool
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        $saved = $this->save();

        if ($saved) {
            Log::info('Feedback active status toggled', [
                'feedback_id' => $this->id,
                'is_active' => $this->is_active
            ]);
        }

        return $saved;
    }

    /**
     * ✅ GET STATISTICS
     * @return array
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'inactive' => self::inactive()->count(),
            'for_candidate' => self::forCandidate()->count(),
            'for_company' => self::forCompany()->count(),
            'active_candidate' => self::forCandidate()->active()->count(),
            'active_company' => self::forCompany()->active()->count(),
        ];
    }

    // ========================================
    // BOOT METHOD
    // ========================================

    /**
     * ✅ BOOT: Auto logging
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feedback) {
            Log::info('Creating feedback', [
                'name' => $feedback->name,
                'for' => $feedback->for
            ]);
        });

        static::created(function ($feedback) {
            Log::info('Feedback created', [
                'feedback_id' => $feedback->id,
                'name' => $feedback->name,
                'for' => $feedback->for
            ]);
        });

        static::updated(function ($feedback) {
            Log::info('Feedback updated', [
                'feedback_id' => $feedback->id,
                'name' => $feedback->name,
                'for' => $feedback->for,
                'is_active' => $feedback->is_active
            ]);
        });

        static::deleted(function ($feedback) {
            Log::info('Feedback deleted', [
                'feedback_id' => $feedback->id,
                'name' => $feedback->name
            ]);
        });
    }
}
