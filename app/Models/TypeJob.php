<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeJob extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationships
     */

    // TypeJob -> Job Postings (One to Many)
    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'type_jobs_id');
    }

    // TypeJob -> Candidates yang prefer tipe job ini (Many to Many)
    public function preferredByCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_type_jobs', 'type_jobs_id', 'candidates_id');
    }
}
