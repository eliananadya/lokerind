<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefferedIndustries extends Model
{
    use HasFactory;
    protected $table = 'preffered_industries';
    protected $fillable = [
        'candidates_id',
        'industries_id',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }

    /**
     * Relasi dengan model Industry
     */
    public function industry()
    {
        return $this->belongsTo(Industries::class, 'industries_id');
    }
}
