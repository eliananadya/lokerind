<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    use HasFactory;

    protected $table = 'days';

    protected $fillable = [
        'name',
    ];
    public function preferredCandidates()
    {
        return $this->belongsToMany(Candidates::class, 'preffered_days', 'days_id', 'candidates_id')
            ->withTimestamps();
    }


    public function jobPostings()
    {
        return $this->belongsToMany(JobPostings::class, 'job_dates', 'days_id', 'job_posting_id');
    }

    public function prefferedDays()
    {
        return $this->hasMany(PrefferedDays::class, 'days_id');
    }

    public function jobDates()
    {
        return $this->hasMany(JobDates::class);
    }
}
