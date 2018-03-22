<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Review;
use Makeable\LaravelReviews\Tests\Stubs\Job;
use Makeable\LaravelReviews\Tests\Stubs\User;
use Makeable\LaravelReviews\Tests\TestCase;

class RevieweeTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewee_has_a_score()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertEquals(5, $review->reviewee->score);
        $this->assertEquals(5, User::withScore()->where('id', $review->reviewee->id)->firstOrFail()->score);
    }

    /** @test * */
    public function the_score_only_applies_to_the_reviewee()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertNull($review->reviewer->score);
    }
}
