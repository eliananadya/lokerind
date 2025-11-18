<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'message',
        'applied_at',
        'rating_candidate',
        'rating_company',
        'review_candidate',
        'review_company',
        'candidates_id',
        'job_postings_id',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidates_id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_postings_id');
    }

    public function historyPoint()
    {
        return $this->hasOne(HistoryPoint::class, 'applications_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'applications_id');
    }

    public function feedbacksFromCandidate()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_applications', 'applications_id', 'feedbacks_id')
            ->wherePivot('given_by', 'candidate')
            ->withPivot('given_by', 'created_at');
    }

    public function feedbacksFromCompany()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_applications', 'applications_id', 'feedbacks_id')
            ->wherePivot('given_by', 'company')
            ->withPivot('given_by', 'created_at');
    }
}
