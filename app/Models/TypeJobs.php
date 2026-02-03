<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeJobs extends Model
{
    use HasFactory;

    protected $table = 'type_jobs';

    protected $fillable = [
        'name',
    ];
    public function preferredCandidates()
    {
        return $this->belongsToMany(Candidates::class, 'preffered_type_jobs', 'type_jobs_id', 'candidates_id')
            ->withTimestamps();
    }



    public function jobPostings()
    {
        return $this->hasMany(JobPostings::class);
    }

    public function prefferedTypeJobs()
    {
        return $this->hasMany(PrefferedTypeJobs::class, 'type_jobs_id');
    }
}
