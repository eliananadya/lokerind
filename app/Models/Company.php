<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'location',
        'description',
        'avg_rating',
        'users_id',
        'industries_id',
    ];

    protected $casts = [
        'avg_rating' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industries_id');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'companies_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany(Candidate::class, 'subscribes', 'companies_id', 'candidates_id')
            ->withPivot('created_at');
    }
}
