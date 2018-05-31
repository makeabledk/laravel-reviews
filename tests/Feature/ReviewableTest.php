<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Tests\Stubs\Job;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewableTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewable_has_a_reviews_score()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(4, $review->reviewable->reviews_score);
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

        $this->assertEquals(4, $review->reviewable->reviews_score);
        $this->assertArraySubset([4, 1], Job::withReviewsScore()->pluck('reviews_score')->toArray());
    }

    /** @test **/
    public function it_eager_loads_score_on_reviews_relation()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $raw = $review->reviewable->reviews->first()->toArray();

        $this->assertEquals(5, $raw['score']);
    }

    /** @test **/
    public function a_reviewable_has_a_with_reviews_count_scope()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(1, Job::withReviewsCount()->first()->reviews_count);
    }
}
