<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Tests\Stubs\User;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewerTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function a_reviewer_has_an_authored_score()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertEquals(5, $review->reviewer->authored_score);
        $this->assertEquals(5, User::withAuthoredScore()->where('id', $review->reviewer->id)->firstOrFail()->authored_score);
    }

    /** @test * */
    public function the_authored_score_only_applies_to_the_reviewer()
    {
        ($review = $this->review())->ratings()->save($this->rating(5, $this->ratingCategory(1)));

        $this->assertNull($review->reviewee->authored_score);
    }
}
