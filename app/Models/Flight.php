<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'Flights';
    protected $primaryKey = 'FlightID';
    public $timestamps = false;

    protected $fillable = [
        'AirlineID',
        'FlightNumber',
        'AircraftID',
        'DepartureAirport',
        'ArrivalAirport',
        'DepartureTime',
        'ArrivalTime',
        'Status'
    ];

    // Quan hệ
    public function airline()
    {
        return $this->belongsTo(Airline::class, 'AirlineID', 'AirlineID');
    }

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class, 'AircraftID');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'DepartureAirport', 'AirportCode');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'ArrivalAirport', 'AirportCode');
    }

    public function fares()
    {
        return $this->hasMany(Fare::class, 'FlightID');
    }
}
