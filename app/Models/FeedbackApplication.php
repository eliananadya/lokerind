<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackApplication extends Model
{
    use HasFactory;
    protected $table = 'feedback_applications';
    protected $fillable = [
        'given_by',
        'feedback_id',
        'application_id',
    ];

    public function application()
    {
        return $this->belongsTo(Applications::class, 'application_id');
    }

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }
}
