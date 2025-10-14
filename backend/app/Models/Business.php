<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Review;

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
     * Scope: filter by created_at date range (YYYY-MM-DD strings supported).
     */
    public function scopeCreatedBetween(Builder $query, $from = null, $to = null): Builder
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope: ensure businesses have at least N reviews. Adds reviews_count.
     */
    public function scopeWithReviewsCountMin(Builder $query, ?int $min): Builder
    {
        if ($min !== null && $min > 0) {
            // Only count approved reviews toward the minimum
            $query->has('reviews', '>=', $min, 'and', function (Builder $q) {
                $q->where('status', Review::STATUS_APPROVED);
            });
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
        $allowed = ['id', 'name', 'rating', 'created_at', 'reviews_count'];
        if (!in_array($column, $allowed, true)) {
            return $query->latest('id');
        }
        if ($column === 'reviews_count') {
            $query->withCount(['reviews' => function (Builder $q) {
                $q->where('status', Review::STATUS_APPROVED);
            }]);
        }
        return $query->orderBy($column, $direction);
    }
}
