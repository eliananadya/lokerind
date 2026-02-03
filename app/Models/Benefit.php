<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $table = 'benefits';

    protected $fillable = [
        'name',
    ];

    /**
     * 8. job_posting_benefits (pivot table) - reverse
     */
    public function jobPostings()
    {
        return $this->belongsToMany(JobPostings::class, 'job_posting_benefits', 'benefit_id', 'job_posting_id')
            ->withPivot('id', 'benefit_type', 'amount')
            ->withTimestamps();
    }
}
