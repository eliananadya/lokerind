<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPostingBenefit extends Model
{
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'benefits_id',
        'job_postings_id',
        'benefit_type',
        'amount'
    ];

    public function benefit()
    {
        return $this->belongsTo(Benefit::class, 'benefits_id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_postings_id');
    }
}
