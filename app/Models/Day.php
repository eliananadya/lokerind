<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function jobDates()
    {
        return $this->hasMany(JobDate::class, 'days_id');
    }

    public function preferredByCandidates()
    {
        return $this->belongsToMany(Candidate::class, 'preffered_days', 'days_id', 'candidates_id');
    }
}
