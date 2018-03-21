<?php

namespace Makeable\LaravelReviews\Tests;

use Faker\Generator;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Makeable\LaravelReviews\Rating;
use Makeable\LaravelReviews\RatingCategory;
use Makeable\LaravelReviews\Review;
use Makeable\LaravelReviews\Tests\Stubs\Job;
use Makeable\LaravelReviews\Tests\Stubs\User;

trait ModelFactory
{
    /**
     * @param Application $app
     */
    protected function registerFactories(Application $app)
    {
        $factory = $app->make(\Illuminate\Database\Eloquent\Factory::class);
        $factory->define(Review::class, function (Generator $faker) {
            return [
                'title' => $faker->sentence,
                'body' => $faker->paragraph,
            ];
        });
    }

    /**
     * @return Job
     */
    protected function job()
    {
        return Job::create([]);
    }

    /**
     * @param $category
     * @param int $value
     * @return mixed
     */
    protected function rating($value, $category)
    {
        return new Rating([
            'rating_category_slug' => $category instanceof RatingCategory ? $category->slug : $category,
            'value' => $value,
        ]);
    }

    /**
     * @param $slug
     * @param int $weight
     * @return RatingCategory
     */
    protected function ratingCategory($weight, $slug = null)
    {
        return RatingCategory::create([
            'slug' => ($slug = $slug ?: app(Generator::class)->slug),
            'name' => Str::title($slug),
            'weight' => $weight,
        ]);
    }

    /**
     * @return Review
     */
    protected function review()
    {
        $review = factory(Review::class)->make();
        $review->reviewee()->associate($this->user());
        $review->reviewer()->associate($this->user());
        $review->reviewable()->associate($this->job());

        return tap($review)->save();
    }

//
//    /**
//     * @return Review
//     */
//    protected function reviewWithRatings()
//    {
//        $review = $this->review();
//        $review->ratings()->saveMany([
//            $this->rating('first'),
//            $this->rating('second'),
//            $this->rating('third'),
//        ]);
//
//        return $review;
//    }

    /**
     * @param array $attributes
     * @return User
     */
    protected function user($attributes = [])
    {
        return User::create(factory(\App\User::class)->make($attributes)->getAttributes());
    }
}
