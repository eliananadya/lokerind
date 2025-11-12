<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationships
     */

    // Industry -> Companies (One to Many)
    public function companies()
    {
        return $this->hasMany(Company::class, 'industries_id');
    }

    // Industry -> Job Postings (One to Many)
    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'industries_id');
    }

    // Industry -> Candidates yang prefer industri ini (Many to Many)
    public function preferredByCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_industries', 'industries_id', 'candidates_id');
    }
}
