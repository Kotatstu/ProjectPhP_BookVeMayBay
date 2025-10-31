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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function fare()
    {
        return $this->belongsTo(Fare::class, 'FareID', 'FareID');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'PaymentMethodID', 'PaymentMethodID');
    }

    // Shortcut quan hệ sâu để truy cập user và cabin class
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Customer::class,
            'CustomerID', // Foreign key on Customers
            'id',         // Foreign key on Users
            'CustomerID', // Local key on Tickets
            'UserID'      // Local key on Customers
        );
    }

    public function cabinClass()
    {
        return $this->hasOneThrough(
            CabinClass::class,
            Fare::class,
            'FareID',       // Foreign key on Fares
            'CabinClassID', // Foreign key on CabinClasses
            'FareID',       // Local key on Tickets
            'CabinClassID'  // Local key on Fares
        );
    }
}
