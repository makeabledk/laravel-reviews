<?php

namespace Makeable\LaravelReviews\Tests\Stubs;

use Makeable\LaravelReviews\Reviewee;
use Makeable\LaravelReviews\Reviewer;

class User extends \App\User
{
    use Reviewee, Reviewer;

    /**
     * @var array
     */
    protected $guarded = [];
}