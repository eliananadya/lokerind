<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'name',
    ];
    public function candidates()
    {
        return $this->belongsToMany(Candidates::class, 'candidates_skills', 'skills_id', 'candidates_id')
            ->withTimestamps();
    }

    public function jobPostings()
    {
        return $this->belongsToMany(JobPostings::class, 'job_posting_skills', 'skills_id', 'job_posting_id')
            ->withTimestamps();
    }
}
