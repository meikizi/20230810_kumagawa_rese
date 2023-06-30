<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    ];

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

}
