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

    public function companies()
    {
        return $this->hasMany(Company::class, 'industries_id');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'industries_id');
    }

    public function preferredByCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_industries', 'industries_id', 'candidates_id');
    }
}
