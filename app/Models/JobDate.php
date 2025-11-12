<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobDate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'job_postings_id',
        'days_id'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_postings_id');
    }

    public function day()
    {
        return $this->belongsTo(Day::class, 'days_id');
    }
}
