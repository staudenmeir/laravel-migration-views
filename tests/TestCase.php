<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase as Base;
use Tests\Models\User;

abstract class TestCase extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        $config = require __DIR__.'/config/database.php';

        $db = new DB;
        $db->addConnection($config[getenv('DB') ?: 'sqlite']);
        $db->setAsGlobal();
        $db->bootEloquent();

        $this->migrate();

        $this->seed();

        Facade::setFacadeApplication(['db' => $db]);
    }

    /**
     * Migrate the database.
     *
     * @return void
     */
    protected function migrate()
    {
        DB::schema()->dropAllTables();
        DB::schema()->dropAllViews();

        DB::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('active');
            $table->timestamps();
        });
    }

    /**
     * Seed the database.
     *
     * @return void
     */
    protected function seed()
    {
        Model::unguard();

        User::create(['name' => 'Doe', 'active' => 1]);
        User::create(['name' => "O'Brien", 'active' => 0]);

        Model::reguard();
    }
}
