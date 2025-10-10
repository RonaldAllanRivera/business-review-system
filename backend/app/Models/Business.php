<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'rating',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope: free-text search by name/description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: filter by rating range.
     */
    public function scopeRatingBetween(Builder $query, $min = null, $max = null): Builder
    {
        if ($min !== null) {
            $query->where('rating', '>=', (float) $min);
        }
        if ($max !== null) {
            $query->where('rating', '<=', (float) $max);
        }
        return $query;
    }

    /**
     * Scope: generic sorter. Accepts `field` or `-field` for desc.
     */
    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        if (!$sort) {
            return $query->latest('id');
        }
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        // Whitelist allowed columns to prevent SQL injection
        $allowed = ['id', 'name', 'rating', 'created_at'];
        if (!in_array($column, $allowed, true)) {
            return $query->latest('id');
        }
        return $query->orderBy($column, $direction);
    }
}
