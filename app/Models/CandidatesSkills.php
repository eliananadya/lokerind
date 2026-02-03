<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatesSkills extends Model
{
    use HasFactory;

    protected $table = 'candidates_skills';

    protected $fillable = [
        'candidates_id',
        'skills_id',
    ];
}
