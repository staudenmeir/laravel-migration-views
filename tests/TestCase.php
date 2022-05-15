<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Base;
use Staudenmeir\LaravelMigrationViews\DatabaseServiceProvider;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;
use Tests\Models\User;

abstract class TestCase extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropAllTables();
        Schema::dropViewIfExists('active_users');
        Schema::dropViewIfExists('test');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('active');
            $table->timestamps();
        });

        Model::unguard();

        User::create(['name' => 'Doe', 'active' => 1]);
        User::create(['name' => "O'Brien", 'active' => 0]);

        Model::reguard();
    }

    protected function getEnvironmentSetUp($app)
    {
        $config = require __DIR__.'/config/database.php';

        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', $config[getenv('DATABASE') ?: 'sqlite']);
    }

    protected function getPackageProviders($app)
    {
        return [DatabaseServiceProvider::class];
    }
}
