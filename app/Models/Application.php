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
        'invited_at',
        'invited_by_company',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'invited_at' => 'datetime',
        'invited_by_company' => 'boolean',
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
        return $this->hasMany(HistoryPoint::class, 'applications_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'applications_id');
    }

    public function feedbacks()
    {
        return $this->belongsToMany(Feedback::class, 'feedback_applications', 'applications_id', 'feedbacks_id')
            ->withPivot('given_by', 'created_at');
    }

    public function feedbacksFromCandidate()
    {
        return $this->feedbacks()->wherePivot('given_by', 'candidate');
    }

    public function feedbacksFromCompany()
    {
        return $this->feedbacks()->wherePivot('given_by', 'company');
    }
}
