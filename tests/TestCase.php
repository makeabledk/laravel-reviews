<?php

namespace Makeable\LaravelReviews\Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Makeable\LaravelReviews\ReviewServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(ReviewServiceProvider::class);

        return $app;
    }

    /**
     * @return User
     */
    protected function user()
    {
        return factory(User::class)->create();
    }
}
