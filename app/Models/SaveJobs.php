<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveJobs extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'save_jobs';
    protected $fillable = [
        'candidates_id',
        'job_posting_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id', 'id');
    }

    /**
     * Get the job posting that was saved
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id', 'id');
    }

    public function getSortDateAttribute()
    {
        return $this->created_at;
    }

    public function savedJobs()
    {
        return $this->belongsToMany(JobPostings::class, 'save_jobs', 'candidates_id', 'job_posting_id');
    }

    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }
}
