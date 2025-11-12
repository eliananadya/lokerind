<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'for', // 'candidate' atau 'company'
    ];

    /**
     * Relationships
     */

    // Feedback -> Applications (Many to Many via pivot)
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'feedback_applications', 'feedbacks_id', 'applications_id')
            ->withPivot('given_by', 'created_at');
    }

    /**
     * Scope untuk filter feedback
     */

    // Get feedback untuk candidate
    public function scopeForCandidate($query)
    {
        return $query->where('for', 'candidate');
    }

    // Get feedback untuk company
    public function scopeForCompany($query)
    {
        return $query->where('for', 'company');
    }
}
