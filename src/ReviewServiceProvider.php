<?php

namespace Makeable\LaravelReviews;

use Illuminate\Support\ServiceProvider;

class ReviewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->mergeConfigFrom(__DIR__.'/../config/laravel-event-store.php', 'laravel-event-store');

        if (! class_exists('CreateReviewsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_reviews_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_reviews_table.php'),
                __DIR__.'/../database/migrations/create_rating_types_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()+1).'_create_rating_types_table.php'),
                __DIR__.'/../database/migrations/create_ratings_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()+2).'_create_ratings_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
