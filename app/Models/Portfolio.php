<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'file',
        'caption',
        'candidates_id'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidates_id');
    }
}
