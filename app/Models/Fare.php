<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fare extends Model
{
    protected $table = 'Fares';
    protected $primaryKey = 'FareID';
    public $timestamps = false;

    protected $fillable = [
        'FlightID',
        'CabinClassID',
        'BasePrice',
        'Tax',
        'Currency',
        'Refundable',
        'Changeable',
        'FareRules',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'FlightID', 'FlightID');
    }

    public function cabinClass()
    {
        return $this->belongsTo(CabinClass::class, 'CabinClassID', 'CabinClassID');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'FareID', 'FareID');
    }
}
