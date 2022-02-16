<?php

namespace Staudenmeir\LaravelMigrationViews;

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
        $this->app->bind(Schema::class, function ($app) {
            return Schema::getSchemaBuilder(
                $app['db']->connection()
            );
        });
    }
}
