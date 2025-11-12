<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackApplication extends Model
{
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'feedback_applications';

    protected $fillable = [
        'feedbacks_id',
        'applications_id',
        'given_by',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedbacks_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'applications_id');
    }
}
