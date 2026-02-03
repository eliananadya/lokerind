<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'reason',
        'status',
        'application_id',
        'user_id',
    ];
    public function application()
    {
        return $this->belongsTo(Applications::class, 'application_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
