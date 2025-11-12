<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationships
     */

    // Skill -> Candidates yang punya skill ini (Many to Many)
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidates_skills', 'skills_id', 'candidates_id');
    }

    // Skill -> Job Postings yang butuh skill ini (Many to Many)
    public function jobPostings()
    {
        return $this->belongsToMany(JobPosting::class, 'job_postings_skills', 'skills_id', 'job_postings_id');
    }
}
