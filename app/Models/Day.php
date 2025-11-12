<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationships
     */

    // Day -> Job Dates (One to Many)
    public function jobDates()
    {
        return $this->hasMany(JobDate::class, 'days_id');
    }

    // Day -> Candidates yang prefer hari ini (Many to Many)
    public function preferredByCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_days', 'days_id', 'candidates_id');
    }
}
