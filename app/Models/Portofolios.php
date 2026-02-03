<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolios extends Model
{
    use HasFactory;
    protected $table = 'portofolios';
    protected $fillable = [
        'file',
        'caption',
        'candidates_id',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }
}
