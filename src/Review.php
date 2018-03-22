<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use SubSelecting;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewee()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewer()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithScore($query)
    {
        return $this->addSubSelect('score', Rating::combinedScore()->whereRaw('ratings.review_id = reviews.id'), $query);
    }

    /**
     * @return mixed
     */
    public function getScoreAttribute()
    {
        return (new ScoreInteraction($this))->getOrLoadScoreAttribute('score');
    }
}
