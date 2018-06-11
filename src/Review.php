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
     * @var array
     */
    protected $casts = [
        'score' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(get_class(app(Rating::class)));
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
     * @param string $name
     * @param Model $model
     * @return Builder
     */
    public function scopeWhereMorph($query, $name, $model)
    {
        return $query->where("{$name}_type", $model->getMorphClass())
            ->where("{$name}_id", $model->getKey());
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
