<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'PaymentMethods';
    protected $primaryKey = 'PaymentMethodID';
    public $timestamps = false; // Thêm dòng này

    protected $fillable = [
        'CustomerID', 'PaymentType', 'Provider', 'AccountNumber', 'ExpiryDate',
    ];

    // Quan hệ ngược lại 1-n với Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
}
