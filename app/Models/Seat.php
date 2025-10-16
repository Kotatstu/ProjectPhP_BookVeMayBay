<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'Seats';
    protected $primaryKey = 'SeatID';
    public $timestamps = false;

    protected $fillable = [
        'AircraftID',
        'SeatNumber',
        'CabinClassID',
        'IsAvailable',
    ];

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class, 'AircraftID', 'AircraftID');
    }

    public function cabinClass()
    {
        return $this->belongsTo(CabinClass::class, 'CabinClassID', 'CabinClassID');
    }

    public function availability()
    {
        return $this->hasMany(SeatAvailability::class, 'SeatID', 'SeatID');
    }
}
