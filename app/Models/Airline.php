<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $table = 'Airlines';
    protected $primaryKey = 'AirlineID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'AirlineID',
        'AirlineName',
        'Country',
        'LogoURL'
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class, 'AirlineID', 'AirlineID');
    }
}
