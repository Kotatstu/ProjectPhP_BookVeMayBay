<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\adminRole;
use App\Models\LoyaltyProgram;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            //'password' => 'hashed',
        ];
    }
    public function isAdmin()
    {
        return AdminRole::where('U_ID', $this->id)->exists();
    }
public function loyaltyProgram()
{
    return $this->hasOne(LoyaltyProgram::class, 'CustomerID');
}
public function getIsMemberAttribute()
{
    // Nếu khách hàng có dòng trong bảng loyalty_programs → là hội viên
    return $this->loyaltyProgram()->exists();
}
public function user()
{
    return $this->belongsTo(User::class, 'UserID', 'id');
}


}
