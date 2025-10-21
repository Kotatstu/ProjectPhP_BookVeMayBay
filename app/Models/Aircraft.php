<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'Aircrafts';
    protected $primaryKey = 'AircraftID';
    public $timestamps = false;

    protected $fillable = [
        'AircraftCode',
        'AircraftType',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'AircraftID', 'AircraftID');
    }

    public function flights()
    {
        return $this->hasMany(Flight::class, 'AircraftID', 'AircraftID');
    }
}
