<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatAvailability extends Model
{
    protected $table = 'SeatAvailability';
    protected $primaryKey = 'AvailabilityID';
    public $timestamps = false;

    protected $fillable = [
        'FlightID',
        'SeatID',
        'IsBooked',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'FlightID', 'FlightID');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'SeatID', 'SeatID');
    }
}
