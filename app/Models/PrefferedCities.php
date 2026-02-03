<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefferedCities extends Model
{
    use HasFactory;
    protected $table = 'preffered_cities';
    protected $fillable = [
        'candidates_id',
        'cities_id',
    ];
    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidates_id');
    }
    public function city()
    {
        return $this->belongsTo(Cities::class, 'cities_id');
    }
}
