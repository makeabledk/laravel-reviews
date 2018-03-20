<?php

namespace Makeable\LaravelReviews\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Makeable\LaravelReviews\Reviewable;
use Makeable\LaravelReviews\Reviewee;

class Job extends Model
{
    use Reviewable;
}