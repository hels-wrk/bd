<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'price'];
    protected $errors;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($dish) {
            if (!$dish->validate()) {
                return false;
            }
        });

        static::updating(function ($dish) {
            if (!$dish->validate()) {
                return false;
            }
        });
    }

    public static array $rules = [
        'name' => 'required|unique:dishes',
        'description' => 'required',
        'image' => 'required',
        'price' => 'required|numeric',
    ];

    public function validate(): bool
    {
        $validator = Validator::make($this->attributes, self::$rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function averageRating(): float|int|null
    {
        return $this->ratings->avg('rating') ?? 0;
    }

    public function rate(int $rating, User $user)
    {
        return $this->ratings()->create([
            'rating' => $rating,
            'user_id' => $user->id,
        ]);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if ($term) {
            return $query->where('name', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        }

        return $query;
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
