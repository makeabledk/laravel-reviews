<?php

use Illuminate\Database\Migrations\Migration;

require __DIR__.'/../../database/migrations/create_reviews_table.php.stub';
require __DIR__.'/../../database/migrations/create_rating_categories_table.php.stub';
require __DIR__.'/../../database/migrations/create_ratings_table.php.stub';

class CreateProductionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        (new CreateReviewsTable())->up();
        (new CreateRatingCategoriesTable())->up();
        (new CreateRatingsTable())->up();
    }
}