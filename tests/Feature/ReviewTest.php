<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Review;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    function a_review_can_be_created_on_the_reviewee_through_the_helper_method()
    {
        $review = ($reviewee = $this->user())->review(
            new Review(['title' => 'foo', 'body' => 'bar']),
            $job = $this->job(),
            $reviewer = $this->user()
        );

        $this->assertEquals($review->id, Review::first()->id);
        $this->assertEquals($reviewee->id, $review->reviewee->id);
        $this->assertEquals($reviewer->id, $review->reviewer->id);
    }

    /** @test **/
    function a_review_has_a_accumulated_rating()
    {
//        $review->rating_score;
//        $review->
    }

//    function a_review_consists_of_reviewer_reviewee_and_reviewable()

}
