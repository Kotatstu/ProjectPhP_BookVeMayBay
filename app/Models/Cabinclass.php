<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabinClass extends Model
{
    protected $table = 'CabinClasses';
    protected $primaryKey = 'CabinClassID';
    public $timestamps = false;

    protected $fillable = [
        'ClassCode',
        'ClassName',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'CabinClassID', 'CabinClassID');
    }

    public function fares()
    {
        return $this->hasMany(Fare::class, 'CabinClassID', 'CabinClassID');
    }
}
