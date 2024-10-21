<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class SchemaTest extends TestCase
{
    public function testCreateView()
    {
        Schema::connection('testing')->createView('active_users', DB::table('users')->where('active', true));

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithColumns()
    {
        $active = Schema::connection('testing')->getConnection()->getDriverName() === 'pgsql' ? 'true' : 1;

        Schema::createView('active_users', "select id from users where active = $active", ['key']);

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
        $this->assertEquals(1, $users[0]->key);
    }

    public function testCreateViewWithBooleanBinding()
    {
        Schema::connection('testing')->createView('test', DB::table('users')->where('active', false));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithObjectBinding()
    {
        $object = new class () {
            public function __toString()
            {
                return "O'Brien";
            }
        };
        Schema::connection('testing')->createView('test', DB::table('users')->where('name', $object));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithStringBinding()
    {
        Schema::connection('testing')->createView('test', DB::table('users')->where('name', "O'Brien"));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithAlgorithm()
    {
        if (!in_array($this->connection, ['mysql', 'mariadb'])) {
            $this->markTestSkipped();
        }

        DB::enableQueryLog();

        Schema::connection('testing')->createView(
            'active_users',
            DB::table('users')->where('active', true),
            algorithm: 'temptable'
        );

        $this->assertStringContainsString('algorithm = temptable', DB::getQueryLog()[0]['query']);
    }

    public function testCreateOrReplaceView()
    {
        Schema::createView('active_users', DB::table('users'));

        Schema::createOrReplaceView('active_users', DB::table('users')->where('active', true));

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateMaterializedView()
    {
        if ($this->connection !== 'pgsql') {
            $this->markTestSkipped();
        }

        Schema::createMaterializedView(
            'active_users',
            DB::table('users')->where('active', true)
        );

        $this->assertDatabaseCount('active_users', 1);

        DB::table('users')->update(['active' => false]);

        $this->assertDatabaseCount('active_users', 1);
    }

    public function testRenameView()
    {
        Schema::createView('active_users', DB::table('users')->where('active', true));

        Schema::renameView('active_users', 'test');

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testDropView()
    {
        Schema::createView('active_users', DB::table('users')->where('active', true));

        Schema::dropView('active_users');

        $this->assertFalse(Schema::hasView('active_users'));
    }

    public function testDropViewIfExists()
    {
        Schema::dropViewIfExists('active_users');

        $this->assertFalse(Schema::hasView('active_users'));
    }

    public function testGetViewColumnListing()
    {
        Schema::createView('active_users', DB::table('users')->where('active', true));

        $columns = Schema::getViewColumnListing('active_users');
        sort($columns);

        $this->assertSame(['active', 'created_at', 'id', 'name', 'updated_at'], $columns);
    }

    public function testRefreshMaterializedView()
    {
        if ($this->connection !== 'pgsql') {
            $this->markTestSkipped();
        }

        Schema::createMaterializedView(
            'active_users',
            DB::table('users')->where('active', true)
        );

        DB::table('users')->update(['active' => false]);

        Schema::refreshMaterializedView('active_users');

        $this->assertDatabaseCount('active_users', 0);
    }
}
