<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostingBenefit extends Model
{
    use HasFactory;
    protected $table = 'job_posting_benefits';
    protected $fillable = [
        'benefit_id',
        'job_posting_id',
        'benefit_type', // ✅ NEW
        'amount' // ✅ NEW
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id');
    }
    public function benefit()
    {
        return $this->belongsTo(Benefit::class, 'benefit_id');
    }
}
