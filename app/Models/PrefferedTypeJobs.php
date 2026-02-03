<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefferedTypeJobs extends Model
{
    use HasFactory;
    protected $table = 'preffered_type_jobs';
    protected $fillable = [
        'candidates_id',
        'type_jobs_id',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }

    /**
     * Relasi dengan model TypeJobs
     */
    public function typeJob()
    {
        return $this->belongsTo(TypeJobs::class, 'type_jobs_id');
    }
}
