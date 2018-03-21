<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCombinedScore($query)
    {
        return $query
            ->selectRaw('ROUND((SUM(ratings.value * rating_categories.weight) / SUM(rating_categories.weight)), '.((int) config('laravel-reviews.score_decimals')).') as score')
            ->leftJoin('rating_categories', 'ratings.rating_category_slug', '=', 'rating_categories.slug');
    }
}
