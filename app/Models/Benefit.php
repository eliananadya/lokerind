<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function jobPostings()
    {
        return $this->belongsToMany(JobPosting::class, 'job_posting_benefits', 'benefits_id', 'job_postings_id')
            ->withPivot('benefit_type', 'amount');
    }
}
