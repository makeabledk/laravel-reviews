<?php

namespace Makeable\LaravelReviews\Tests\Stubs;

use Illuminate\Database\Eloquent\Builder;
use Makeable\LaravelReviews\Reviewee;
use Makeable\LaravelReviews\Reviewer;
use Makeable\LaravelReviews\ScoreInteraction;

class User extends \App\User
{
    use Reviewee, Reviewer;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithPublishedScore($query)
    {
        return (new ScoreInteraction($this))->subSelectScoreForRelatedReviews('score',
            $this->reviews()->where('created_at', '<=', now()), $query);
    }
}
