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
        return $this->addSubSelect($name,
            $this->mergeAdditionalWheresFromRelation(
                Rating::combinedScore()
                    ->leftJoin('reviews', 'ratings.review_id', '=', 'reviews.id')
                    ->where($relation->getQualifiedMorphType(), $this->model->getMorphClass())
                    ->whereRaw($relation->getQualifiedForeignKeyName().' = '.$this->model->getQualifiedKeyName()),
                $relation
            ),
            $query
        );
    }

    /**
     * @param Builder $query
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     * @return Builder
     */
    protected function mergeAdditionalWheresFromRelation($query, $relation)
    {
        // A regular MorphMany relation is expected
        // to have exactly 3 rows in 'wheres'.
        // We won't merge those to our query.
        $expectedRelationWheres = 3;

        return tap($query)->mergeWheres(
            // Filter wheres
            collect($relation->getBaseQuery()->wheres)
                ->slice($expectedRelationWheres)
                ->map(\Closure::fromCallable([$this, 'qualifyWhere']))
                ->toArray(),

            // Filter bindings
            collect($relation->getBaseQuery()->getBindings())
                ->slice(collect($relation->getBaseQuery()->wheres)
                    ->slice(0, $expectedRelationWheres)
                    ->filter(function ($where) {
                        return array_key_exists('value', $where);
                    })->count())
                ->toArray()
        );
    }

    /**
     * @param array $wheres
     * @return array
     */
    protected function qualifyWhere(array $where)
    {
        if (strpos($where['column'], '.') === false) {
            $where['column'] = 'reviews.'.$where['column'];
        }

        return $where;
    }
}
