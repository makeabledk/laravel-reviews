<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasScore
{
    use HasSubSelects;

    /**
     * @return mixed
     */
    protected function getOrLoadScoreAttribute($attribute)
    {
        if (! array_key_exists('score', $this->attributes)) {
            $scope = camel_case("with_$attribute");
            $this->attributes[$attribute] = $this->newQuery()->where($this->getKeyName(), $this->getKey())->$scope()->firstOrFail()->score;
            $this->syncOriginalAttribute($attribute);
        }

        return $this->attributes['score'];
    }

    /**
     * @param MorphMany $reviewsRelation
     * @return Builder
     */
    protected function selectScoreForRelatedReviews($reviewsRelation)
    {
        return Rating::combinedScore()
            ->leftJoin('reviews', 'ratings.review_id', '=', 'reviews.id')
            ->where($reviewsRelation->getQualifiedMorphType(), $this->getMorphClass())
            ->whereRaw($reviewsRelation->getQualifiedForeignKeyName().' = '.$this->getQualifiedKeyName());
    }
}
