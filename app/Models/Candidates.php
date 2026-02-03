<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidates extends Model
{
    use HasFactory;

    protected $table = 'candidates';

    protected $fillable = [
        'name',
        'gender',
        'description',
        'birth_date',
        'level_mandarin',
        'level_english',
        'min_height',
        'min_weight',
        'min_salary',
        'point',
        'user_id',
        'phone_number',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'point' => 'integer',
        'min_salary' => 'decimal:2',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Applications::class, 'candidates_id');
    }

    public function saveJobs()
    {
        return $this->hasMany(SaveJobs::class, 'candidates_id');
    }

    public function portofolios()
    {
        return $this->hasMany(Portofolios::class, 'candidates_id');
    }

    /**
     * ✅ RELASI: Ke Skills (Many-to-Many)
     * FIXED: candidates_skills dengan candidates_id dan skills_id
     */
    public function skills()
    {
        return $this->belongsToMany(
            Skills::class,
            'candidates_skills',     // ✅ Table name
            'candidates_id',         // ✅ Foreign key
            'skills_id'              // ✅ Related key
        );
    }

    /**
     * ✅ RELASI: Ke Cities (Many-to-Many) - Preferred Cities
     * FIXED: preffered_cities (TYPO dengan double 'f')
     */
    public function preferredCities()
    {
        return $this->belongsToMany(
            Cities::class,
            'preffered_cities',      // ✅ TYPO: double 'f'
            'candidates_id',         // ✅ Foreign key
            'cities_id'              // ✅ Related key
        );
    }

    /**
     * ✅ RELASI: Ke TypeJobs (Many-to-Many) - Preferred Type Jobs
     * FIXED: preffered_type_jobs (TYPO dengan double 'f')
     */
    public function preferredTypeJobs()
    {
        return $this->belongsToMany(
            TypeJobs::class,
            'preffered_type_jobs',   // ✅ TYPO: double 'f'
            'candidates_id',         // ✅ Foreign key
            'type_jobs_id'           // ✅ Related key
        );
    }

    /**
     * ✅ RELASI: Ke Industries (Many-to-Many) - Preferred Industries
     * FIXED: preffered_industries (TYPO dengan double 'f')
     */
    public function preferredIndustries()
    {
        return $this->belongsToMany(
            Industries::class,
            'preffered_industries',
            'candidates_id',
            'industries_id'
        );
    }

    // Alias supaya kode lama yang pakai industries() tetap jalan
    public function industries()
    {
        return $this->preferredIndustries();
    }

    /**
     * ✅ RELASI: Ke Days (Many-to-Many) - Preferred Days
     * FIXED: preffered_days (TYPO dengan double 'f')
     */
    public function preferredDays()
    {
        return $this->belongsToMany(
            Days::class,
            'preffered_days',        // ✅ TYPO: double 'f'
            'candidates_id',         // ✅ Foreign key
            'days_id'                // ✅ Related key
        );
    }

    public function days()
    {
        return $this->preferredDays();
    }

    public function historyPoints()
    {
        return $this->hasMany(HistoryPoint::class, 'candidates_id');
    }

    public function jobMatches()
    {
        return $this->hasMany(JobCandidateMatch::class, 'candidate_id');
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getFormattedBirthDateAttribute()
    {
        return $this->birth_date ? $this->birth_date->format('d M Y') : '-';
    }

    public function getGenderLabelAttribute()
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    public function getLevelMandarinLabelAttribute()
    {
        return match ($this->level_mandarin) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'expert' => 'Ahli',
            default => '-'
        };
    }

    public function getLevelEnglishLabelAttribute()
    {
        return match ($this->level_english) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'expert' => 'Ahli',
            default => '-'
        };
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->whereHas('user');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByMinPoint($query, $minPoint)
    {
        return $query->where('point', '>=', $minPoint);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    public function hasAppliedTo($jobPostingId)
    {
        return $this->applications()
            ->where('job_posting_id', $jobPostingId)
            ->exists();
    }

    public function hasSavedJob($jobPostingId)
    {
        return $this->saveJobs()
            ->where('job_posting_id', $jobPostingId)
            ->exists();
    }

    public function getApplicationStatus($jobPostingId)
    {
        $application = $this->applications()
            ->where('job_posting_id', $jobPostingId)
            ->first();

        return $application ? $application->status : null;
    }

    public function addPoint($amount, $reason = null, $applicationId = null)
    {
        $oldPoint = $this->point;
        $newPoint = $oldPoint + $amount;

        $this->update(['point' => $newPoint]);

        HistoryPoint::record(
            candidateId: $this->id,
            oldPoint: $oldPoint,
            newPoint: $newPoint,
            applicationId: $applicationId,
            reason: $reason
        );

        return $newPoint;
    }

    public function subtractPoint($amount, $reason = null, $applicationId = null)
    {
        $oldPoint = $this->point;
        $newPoint = max(0, $oldPoint - $amount);

        $this->update(['point' => $newPoint]);

        HistoryPoint::record(
            candidateId: $this->id,
            oldPoint: $oldPoint,
            newPoint: $newPoint,
            applicationId: $applicationId,
            reason: $reason
        );

        return $newPoint;
    }

    public function getPointHistory($limit = null)
    {
        return HistoryPoint::getForCandidate($this->id, $limit);
    }
}
