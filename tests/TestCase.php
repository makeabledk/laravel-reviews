<?php

namespace Makeable\LaravelReviews\Tests;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Makeable\LaravelReviews\ReviewsServiceProvider;
use Schema;

class TestCase extends BaseTestCase
{
    use ModelFactory;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('DB_CONNECTION=mysql'); // using sqlite will cause rounding issues in score calculation

        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(ReviewsServiceProvider::class);
        $app->afterResolving('migrator', function (Migrator $migrator) {
            $migrator->path(__DIR__.'/migrations/');
        });

        // MySQL 5.6 compatibility
        Schema::defaultStringLength(191);

        $this->registerFactories($app);

        return $app;
    }
}
