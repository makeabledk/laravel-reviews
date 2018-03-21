<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Review;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_review_can_be_created_on_the_reviewee_through_the_helper_method()
    {
        $review = ($reviewee = $this->user())->review(
            // Review
            (new Review(['title' => 'foo', 'body' => 'bar']))
                ->reviewable()->associate($this->job())
                ->reviewer()->associate($reviewer = $this->user()),

            // Ratings
            collect([
                $this->rating(1, $this->ratingCategory(1)),
                $this->rating(1, $this->ratingCategory(1)),
            ])
        );

        $this->assertEquals($review->id, Review::first()->id);
        $this->assertEquals($reviewee->id, $review->reviewee->id);
        $this->assertEquals($reviewer->id, $review->reviewer->id);
    }

    /** @test **/
    public function a_review_has_a_score()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        $this->assertEquals(4, $review->score);
    }

    /** @test **/
    public function the_score_is_based_only_on_the_reviews_own_ratings()
    {
        ($review = $this->review())->ratings()->saveMany([
            $this->rating(5, $this->ratingCategory(1)),
            $this->rating(3, $this->ratingCategory(1)),
        ]);

        ($anotherReview = $this->review())->ratings()->saveMany([
            $this->rating(1, $this->ratingCategory(100)),
       ]);

        $this->assertEquals(4, $review->score);
        $this->assertArraySubset([4, 1], Review::withScore()->pluck('score')->toArray());
    }

    /** @test **/
    public function a_review_can_save_after_having_loaded_the_score()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $review->score; // fetch the score
        $review->title = 'foo bar';
        $review->save();

        $this->assertEquals('foo bar', $review->title);

        // Same applies for pro-loaded scores
        $review = Review::withScore()->firstOrFail();
        $review->title = 'foo baz';
        $review->save();

        $this->assertEquals('foo baz', $review->title);
    }
}
