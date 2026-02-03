<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostingSkills extends Model
{
    use HasFactory;
    protected $table = 'job_posting_skills';
    protected $fillable = [
        'job_posting_id',
        'skills_id',
    ];
    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id');
    }
    public function skill()
    {
        return $this->belongsTo(Skills::class, 'skills_id');
    }
}
