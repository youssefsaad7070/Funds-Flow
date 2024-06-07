<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    // protected $fillable = [
    //     'user_id',
    //     'name',
    //     'tax_card_number',
    //     'email',
    //     'description',
    // ];

    protected $guarded;
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
