<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;

trait HasScore
{
    /**
     * @param Builder $query
     * @return Builder
     */
    abstract public function scopeWithScore($query);

    /**
     * @return mixed
     */
    public function getScoreAttribute()
    {
        if (! array_key_exists('score', $this->attributes)) {
            $this->attributes['score'] = $this->newQuery()->where('id', $this->getKey())->withScore()->firstOrFail()->score;
            $this->syncOriginalAttribute('score');
        }

        return $this->attributes['score'];
    }
}
