<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $table = 'LoyaltyPrograms';
    protected $primaryKey = 'LoyaltyID';

    protected $fillable = [
        'CustomerID', 'MembershipLevel', 'Points',
    ];

    // Quan hệ ngược lại 1-1 với Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
}
