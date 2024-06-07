<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestmentOpportunity extends Model
{
    use HasFactory;
    // , HasUuids;
    protected $guarded;
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'investments', 'opportunity_id', 'investor_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'opportunity_id');
    }
}
