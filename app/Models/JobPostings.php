<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JobPostings extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'title',
        'description',
        'salary',
        'type_salary',
        'address',
        'min_age',
        'max_age',
        'min_height',
        'min_weight',
        'verification_status',
        'status',
        'gender',
        'open_recruitment',
        'close_recruitment',
        'slot',
        'level_mandarin',
        'level_english',
        'has_interview',
        'industries_id',
        'companies_id',
        'type_jobs_id',
        'cities_id',
    ];

    protected $casts = [
        'open_recruitment' => 'datetime',
        'close_recruitment' => 'datetime',
        'has_interview' => 'boolean',
    ];


    public function benefit()
    {
        return $this->belongsToMany(Benefit::class, 'job_posting_benefits', 'job_posting_id', 'benefit_id')
            ->withPivot('benefit_type', 'amount') // ✅ Tetap ada karena ada kolom tambahan
            ->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'job_posting_skills', 'job_posting_id', 'skills_id')
            ->withTimestamps();
    }

    public function savedByCandidates()
    {
        return $this->belongsToMany(Candidates::class, 'save_jobs', 'job_posting_id', 'candidates_id')
            ->withTimestamps();
    }

    // ===== MANY-TO-MANY WITH PIVOT ===== ✅

    /**
     * 8. job_posting_benefits (pivot table)
     */

    // ===== ONE-TO-MANY RELATIONS =====

    public function benefits()
    {
        return $this->hasMany(JobPostingBenefit::class, 'job_posting_id')->with('benefit');
    }

    public function jobDatess()
    {
        return $this->hasMany(JobDates::class, 'job_posting_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Applications::class, 'job_posting_id', 'id');
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidates::class, 'applications');
    }

    public function days()
    {
        return $this->belongsToMany(Days::class, 'job_dates', 'job_posting_id', 'days_id');
    }

    // ===== BELONGS TO RELATIONS =====

    public function industry()
    {
        return $this->belongsTo(Industries::class, 'industries_id');
    }

    public function typeJobs()
    {
        return $this->belongsTo(TypeJobs::class, 'type_jobs_id');
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'cities_id');
    }

    public function company()
    {
        return $this->belongsTo(Companies::class, 'companies_id', 'id');
    }

    // ===== AUTO CLOSE METHODS =====

    public function shouldBeClosed()
    {
        if ($this->status !== 'Open') {
            return false;
        }

        $lastJobDate = $this->jobDatess()
            ->orderBy('date', 'desc')
            ->first();

        if (!$lastJobDate) {
            return false;
        }

        $lastDate = Carbon::parse($lastJobDate->date);
        $closeDate = $lastDate->addDay();

        return Carbon::today()->gte($closeDate);
    }

    public function getLastJobDate()
    {
        return $this->jobDatess()
            ->orderBy('date', 'desc')
            ->first();
    }

    public function getAutoCloseDate()
    {
        $lastJobDate = $this->getLastJobDate();

        if (!$lastJobDate) {
            return null;
        }

        return Carbon::parse($lastJobDate->date)->addDay();
    }

    public function autoCloseIfNeeded()
    {
        if ($this->shouldBeClosed()) {
            $this->update([
                'status' => 'Closed',
                'close_recruitment' => now()
            ]);

            \Log::info('Job posting auto-closed', [
                'job_id' => $this->id,
                'job_title' => $this->title
            ]);

            return true;
        }

        return false;
    }
}
