<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'rate',
        'review',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function shops()
    {
        return $this->belongsTo(Shop::class);
    }
}
