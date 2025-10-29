<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Tickets';
    protected $primaryKey = 'TicketID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'FlightID',
        'FareID',
        'PaymentMethodID',
        'BookingDate',
        'TotalAmount',
        'Status'
    ];
}
