<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDates extends Model
{
    use HasFactory;

    protected $table = 'job_dates';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'job_posting_id',
        'days_id',
    ];

    protected $casts = [
        'date' => 'datetime', // Pastikan 'date' di-cast menjadi objek Carbon
    ];

    public function day()
    {
        return $this->belongsTo(Days::class, 'days_id');
    }
    public function days()
    {
        return $this->belongsTo(Days::class, 'days_id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPostings::class, 'job_posting_id');
    }
}
