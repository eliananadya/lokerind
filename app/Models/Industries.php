<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industries extends Model
{
    use HasFactory;

    protected $table = 'industries';

    protected $fillable = [
        'name',
    ];

    /**
     * ✅ Many-to-many dengan Candidates (pivot: preffered_industries)
     * Tanpa withPivot('id') karena sudah dihapus
     */
    public function preferredCandidates()
    {
        return $this->belongsToMany(
            Candidates::class,
            'preffered_industries',
            'industries_id',
            'candidates_id'
        )->withTimestamps();
    }

    /**
     * ✅ One-to-many dengan JobPostings
     */
    public function jobPostings()
    {
        return $this->hasMany(JobPostings::class, 'industries_id');
    }

    /**
     * ✅ One-to-many dengan PrefferedIndustries (jika perlu akses pivot model)
     */
    public function prefferedIndustries()
    {
        return $this->hasMany(PrefferedIndustries::class, 'industries_id');
    }

    /**
     * ✅ One-to-many dengan Companies
     */
    public function companies()
    {
        return $this->hasMany(Companies::class, 'industries_id');
    }
}
