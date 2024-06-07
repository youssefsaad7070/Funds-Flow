<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'national_id',
        'photo',
        'password',
        'is_approved',
        'id_card_photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function business(){
        return $this->hasOne(Business::class);
    }
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function investor()
    {
        return $this->hasOne(Investor::class);
    }

    public function investors()
    {
        return $this->hasMany(Investor::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function investmentOpportunities()
    {
        return $this->hasMany(InvestmentOpportunity::class, 'business_id');
    }

    public function investmentOpportunitiesAdmin()
    {
        return $this->hasMany(InvestmentOpportunity::class, 'admin_id');
    }

    public function investedOpportunities()
    {
        return $this->belongsToMany(InvestmentOpportunity::class, 'investments', 'investor_id', 'opportunity_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'investor_id');
    }
}
