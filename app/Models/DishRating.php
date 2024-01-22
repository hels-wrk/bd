<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishRating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'dish_id',
        'rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the dish that owns the rating.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
