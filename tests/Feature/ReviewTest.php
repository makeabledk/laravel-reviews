<?php

namespace Makeable\LaravelReviews\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Makeable\LaravelReviews\Review;
use Makeable\LaravelReviews\Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    function it_works()
    {
        Review::create(['reviewable_type' => 'test', 'reviewable_id' => 1]);

        $this->assertEquals(1, Review::count());
    }
}
