<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'Customers';
    protected $primaryKey = 'CustomerID';
    public $timestamps = false;

    protected $fillable = [
        'UserID', 'Gender', 'DateOfBirth', 'Phone', 'Nationality', 'CreatedAt',
    ];

    // Quan hệ 1-1 với User
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    // Quan hệ 1-n với PaymentMethods
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'CustomerID', 'CustomerID');
    }

    // Quan hệ 1-1 với LoyaltyProgram
    public function loyaltyProgram()
    {
        return $this->hasOne(LoyaltyProgram::class, 'CustomerID', 'CustomerID');
    }

    // ✅ Hàm kiểm tra khách hàng có phải hội viên không
    public function getIsMemberAttribute(): bool
    {
        return $this->loyaltyProgram()->exists();
    }
    
}
