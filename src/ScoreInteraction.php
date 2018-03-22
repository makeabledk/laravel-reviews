<?php

namespace Makeable\LaravelReviews;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ScoreInteraction
{
    use SubSelecting;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $attribute
     * @return mixed
     */
    public function getOrLoadScoreAttribute($attribute)
    {
        return $this->model->getOriginal($attribute, function () use ($attribute) {
            $scope = camel_case("with_$attribute");
            $this->model->$attribute = $score = $this->model->newQuery()->where($this->model->getKeyName(), $this->model->getKey())->$scope()->firstOrFail()->$attribute;
            $this->model->syncOriginalAttribute($attribute);
            return $score;
        });
    }

    /**
     * @param string $name
     * @param MorphMany $relation
     * @param Builder $query
     * @return Builder
     */
    public function subSelectScoreForRelatedReviews($name, $relation, $query)
    {
        return $this->addSubSelect($name, Rating::combinedScore()
            ->leftJoin('reviews', 'ratings.review_id', '=', 'reviews.id')
            ->where($relation->getQualifiedMorphType(), $this->model->getMorphClass())
            ->whereRaw($relation->getQualifiedForeignKeyName().' = '.$this->model->getQualifiedKeyName()),
        $query);
    }
}
