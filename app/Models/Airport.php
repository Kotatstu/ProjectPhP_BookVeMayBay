<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'Airports';
    protected $primaryKey = 'AirportCode';
    public $incrementing = false; // KHÔNG tự tăng
    protected $keyType = 'string'; // KHÓA CHÍNH là chuỗi
    public $timestamps = false;

    protected $fillable = [
        'AirportCode',
        'AirportName',
        'City',
        'Country',
        'TimeZone',
    ];

    public function departures()
    {
        return $this->hasMany(Flight::class, 'DepartureAirport', 'AirportCode');
    }

    public function arrivals()
    {
        return $this->hasMany(Flight::class, 'ArrivalAirport', 'AirportCode');
    }
}
