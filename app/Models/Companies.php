<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'phone_number',
        'location',
        'description',
        'avg_rating',
        'user_id',
        'industries_id',
    ];

    protected $casts = [
        'avg_rating' => 'decimal:2',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPostings::class, 'companies_id', 'id');
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribes::class, 'companies_id');
    }

    public function industries()
    {
        return $this->belongsTo(Industries::class, 'industries_id');
    }
    public function candidates()
    {
        return $this->belongsToMany(Candidates::class, 'subscribes', 'companies_id', 'candidates_id')
            ->withTimestamps();
    }

    public function applications()
    {
        return $this->hasManyThrough(
            \App\Models\Applications::class,
            \App\Models\JobPostings::class,
            'companies_id',
            'job_posting_id',
            'id',
            'id'
        );
    }

    public function reviews()
    {
        return $this->hasManyThrough(
            \App\Models\Applications::class,
            \App\Models\JobPostings::class,
            'companies_id',
            'job_posting_id',
            'id',
            'id'
        )->whereNotNull('rating_company')->whereNotNull('review_company');
    }
}
