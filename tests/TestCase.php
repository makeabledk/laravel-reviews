<?php

namespace Makeable\LaravelReviews\Tests;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Makeable\LaravelReviews\ReviewServiceProvider;
use Makeable\LaravelReviews\Tests\Stubs\Job;
use Makeable\LaravelReviews\Tests\Stubs\User;

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
        $app->afterResolving('migrator', function (Migrator $migrator) {
            $migrator->path(__DIR__.'/migrations/');
        });

        return $app;
    }

    /**
     * @return Job
     */
    protected function job()
    {
        return Job::create([]);
    }

    /**
     * @param array $attributes
     * @return User
     */
    protected function user($attributes = [])
    {
        return User::create(factory(\App\User::class)->make($attributes)->getAttributes());
    }
}
