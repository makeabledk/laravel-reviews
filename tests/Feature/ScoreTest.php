<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Rating;
use Makeable\LaravelReviews\Tests\TestCase;

class ScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_calculates_the_score_based_on_rating_values_and_weights()
    {
        $this->review()->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(0.5)),
            $this->rating(1, $this->ratingCategory(0.25)),
            $this->rating(1, $this->ratingCategory(0.25)),
        ]);

        $this->assertEquals(3, Rating::combinedScore()->first()->score);
    }

    /** @test **/
    public function weights_are_simply_relative_to_each_other()
    {
        $this->review()->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(2)),
            $this->rating(1, $this->ratingCategory(1)),
            $this->rating(1, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(3, Rating::combinedScore()->first()->score);
    }

    /** @test **/
    public function score_will_return_as_a_decimal()
    {
        $this->review()->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(4)),
            $this->rating(1, $this->ratingCategory(1)),
            $this->rating(1, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(
            round((5 * 4 + 1 * 1 + 1 * 1) / (4 + 1 + 1), 1), // 3,7
            Rating::combinedScore()->first()->score
        );
    }

    /** @test **/
    public function it_rounds_to_specified_decimals_in_config()
    {
        config()->set('laravel-reviews.score_decimals', 3);

        $this->review()->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(2)),
            $this->rating(4, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(4.667, Rating::combinedScore()->first()->score);

        config()->set('laravel-reviews.score_decimals', 1);
    }
}
