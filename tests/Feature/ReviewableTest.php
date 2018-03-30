<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Tests\Stubs\Job;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewableTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewable_has_a_score()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(4, $review->reviewable->score);
    }

    /** @test * */
    public function the_score_is_based_only_on_the_reviewables_own_ratings()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        ($anotherReview = $this->review())->ratings()->saveMany([
            $this->rating(1, $this->ratingCategory(100)),
        ]);

        $this->assertEquals(4, $review->reviewable->score);
        $this->assertArraySubset([4, 1], Job::withScore()->pluck('score')->toArray());
    }

    /** @test **/
    function it_eager_loads_score_on_reviews_relation()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $raw = $review->reviewable->reviews->first()->toArray();

        $this->assertEquals(5, $raw['score']);
    }
}
