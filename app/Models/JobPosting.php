<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'salary',
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
        'level_english',
        'level_mandarin',
        'type_jobs_id',
        'industries_id',
        'companies_id',
        'cities_id',
        'has_interview',
    ];

    protected $casts = [
        'open_recruitment' => 'datetime',
        'close_recruitment' => 'datetime',
        'has_interview' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function typeJob()
    {
        return $this->belongsTo(TypeJob::class, 'type_jobs_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industries_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }

    public function jobDates()
    {
        return $this->hasMany(JobDate::class, 'job_postings_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_postings_id');
    }

    // Skills yang dibutuhkan
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_postings_skills', 'job_postings_id', 'skills_id');
    }

    // Benefits
    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'job_posting_benefits', 'job_postings_id', 'benefits_id')
            ->withPivot('benefit_type', 'amount');
    }

    // Kandidat yang save lowongan ini
    public function savedByUsers()
    {
        return $this->belongsToMany(Candidate::class, 'save_jobs', 'job_postings_id', 'candidates_id')
            ->withPivot('created_at');
    }
}
