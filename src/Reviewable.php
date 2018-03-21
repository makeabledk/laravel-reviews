<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reviewable
{
    use HasScore,
        HasSubSelects;

    /**
     * @return MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithScore($query)
    {
        return $this->addSubSelect('score',
            Rating::combinedScore()
                ->leftJoin('reviews', 'ratings.review_id', '=', 'reviews.id')
                ->where('reviews.reviewable_type', $this->getMorphClass())
                ->whereRaw('reviews.reviewable_id = '. $this->getTable().'.id'),
            $query);
    }
}
