<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Tests\Stubs\User;
use Makeable\LaravelReviews\Tests\TestCase;

class RevieweeTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewee_has_a_score()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertEquals(5, $review->reviewee->reviews_score);
        $this->assertEquals(5, User::withReviewsScore()->where('id', $review->reviewee->id)->firstOrFail()->reviews_score);
    }

    /** @test * */
    public function the_score_only_applies_to_the_reviewee()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertNull($review->reviewer->reviews_score);
    }

    /** @test **/
    public function associated_reviews_can_be_queried_through_reviewee()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        list($reviewee, $reviewable) = [$review->reviewee, $review->reviewable];

        $this->assertEquals(1, $reviewee->reviews()->whereMorph('reviewable', $reviewable)->count());
    }

    /** @test **/
    public function it_eager_loads_score_on_reviews_relation()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $raw = $review->reviewee->reviews->first()->toArray();

        $this->assertEquals(5, $raw['score']);
    }

    /** @test **/
    public function a_reviewee_has_a_with_reviews_count_scope()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(1, $review->reviewee()->withReviewsCount()->first()->reviews_count);
    }
}
