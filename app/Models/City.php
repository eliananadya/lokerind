<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'cities_id');
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_cities', 'cities_id', 'candidates_id');
    }
}
