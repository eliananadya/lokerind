<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribes extends Model
{
    use HasFactory;
    protected $table = 'subscribes';
    public $timestamps = true;
    protected $fillable = [
        'candidates_id',
        'companies_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }

    /**
     * Relasi dengan model Company
     */
    public function company()
    {
        return $this->belongsTo(Companies::class, 'companies_id');
    }
}
