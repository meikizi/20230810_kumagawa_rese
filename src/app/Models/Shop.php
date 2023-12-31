<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area',
        'genre',
        'overview',
        'path',
    ];

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = empty($value) ? null : $value;
    }

    public function scopeAreaSearch($query, $area) {
        if (!empty($area)) {
            $query->where('area', $area);
        }
    }

    public function scopeGenreSearch($query, $genre) {
        if (!empty($genre)) {
            $query->where('genre', $genre);
        }
    }

    public function scopeNameSearch($query, $name) {
        if (!empty($name)) {
            $query->where('name','like', '%' . $name . '%');
        }
    }

    /**
     * ユーザー関連付け
     * 多対多 中間テーブル
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('date', 'time', 'number');
    }

    public function bookMarks()
    {
        return $this->hasMany(BookMark::class);
    }

    public function shopReviews()
    {
        return $this->hasMany(ShopReview::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

}
