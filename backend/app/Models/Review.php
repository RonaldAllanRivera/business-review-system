<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'rating',
        'title',
        'body',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by business id.
     */
    public function scopeByBusiness(Builder $query, ?int $businessId): Builder
    {
        return $businessId ? $query->where('business_id', $businessId) : $query;
    }

    /**
     * Scope: filter by user id.
     */
    public function scopeByUser(Builder $query, ?int $userId): Builder
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }

    /**
     * Scope: sort by allowed columns. Accepts `field` or `-field`.
     */
    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        if (!$sort) {
            return $query->latest('id');
        }
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        $allowed = ['id', 'rating', 'created_at'];
        if (!in_array($column, $allowed, true)) {
            return $query->latest('id');
        }
        return $query->orderBy($column, $direction);
    }
}
