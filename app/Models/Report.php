<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reason',
        'status',
        'created_at',
        'applications_id',
        'users_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'applications_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
