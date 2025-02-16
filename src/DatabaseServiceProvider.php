<?php

namespace Staudenmeir\LaravelMigrationViews;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Schema::class, function (Application $app) {
            /** @var \Illuminate\Database\DatabaseManager $db */
            $db = $app->make('db');

            return Schema::getSchemaBuilder(
                $db->connection()
            );
        });
    }
}
