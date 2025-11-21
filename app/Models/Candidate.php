<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'gender',
        'phone_number',
        'description',
        'birth_date',
        'level_mandarin',
        'level_english',
        'point',
        'avg_rating',
        'min_height',
        'min_weight',
        'min_salary',
        'persentage_acc',
        'users_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'point' => 'integer',
        'avg_rating' => 'decimal:2',
        'persentage_acc' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'candidates_id');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'candidates_id');
    }

    public function historyPoints()
    {
        return $this->hasMany(HistoryPoint::class, 'candidates_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'candidates_skills', 'candidates_id', 'skills_id');
    }

    public function preferredCities()
    {
        return $this->belongsToMany(City::class, 'preffered_cities', 'candidates_id', 'cities_id');
    }

    public function preferredDays()
    {
        return $this->belongsToMany(Day::class, 'preffered_days', 'candidates_id', 'days_id');
    }

    public function preferredIndustries()
    {
        return $this->belongsToMany(Industry::class, 'preffered_industries', 'candidates_id', 'industries_id');
    }

    public function preferredTypeJobs()
    {
        return $this->belongsToMany(TypeJob::class, 'preffered_type_jobs', 'candidates_id', 'type_jobs_id');
    }

    public function savedJobs()
    {
        return $this->belongsToMany(JobPosting::class, 'save_jobs', 'candidates_id', 'job_postings_id')
            ->withPivot('created_at');
    }

    public function subscribedCompanies()
    {
        return $this->belongsToMany(Company::class, 'subscribes', 'candidates_id', 'companies_id')
            ->withPivot('created_at');
    }
}
