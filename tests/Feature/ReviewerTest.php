<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Tests\Stubs\User;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewerTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewer_has_an_authored_reviews_score()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertEquals(5, $review->reviewer->authored_reviews_score);
        $this->assertEquals(5, User::withAuthoredReviewsScore()->where('id', $review->reviewer->id)->firstOrFail()->authored_reviews_score);
    }

    /** @test * */
    public function the_authored_score_only_applies_to_the_reviewer()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertNull($review->reviewee->authored_reviews_score);
    }

    /** @test **/
    public function it_eager_loads_score_authored_reviews_relation()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $raw = $review->reviewer->authoredReviews->first()->toArray();

        $this->assertEquals(5, $raw['score']);
    }

    /** @test **/
    public function a_reviewer_has_a_with_authored_reviews_count_scope()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(1, $review->reviewer()->withAuthoredReviewsCount()->first()->authored_reviews_count);
    }
}
