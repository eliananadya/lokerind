<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'name',
    ];


    public function jobPostings()
    {
        return $this->hasMany(JobPostings::class);
    }
    public function preferredCandidates()
    {
        return $this->belongsToMany(Candidates::class, 'preffered_cities', 'cities_id', 'candidates_id')
            ->withTimestamps();
    }


    public function prefferedCities()
    {
        return $this->hasMany(PrefferedCities::class, 'cities_id');
    }
}
