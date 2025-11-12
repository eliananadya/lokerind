<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPoint extends Model
{
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'old_point',
        'new_point',
        'created_at',
        'candidates_id',
        'applications_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidates_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'applications_id');
    }
}
