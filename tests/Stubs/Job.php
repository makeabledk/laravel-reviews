<?php

namespace Makeable\LaravelReviews\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Makeable\LaravelReviews\Reviewable;

class Job extends Model
{
    use Reviewable;
}
