<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefferedDays extends Model
{
    use HasFactory;
    protected $table = 'preffered_days';
    protected $fillable = [
        'candidates_id',
        'days_id',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }
    public function day()
    {
        return $this->belongsTo(Days::class, 'days_id');
    }
}
