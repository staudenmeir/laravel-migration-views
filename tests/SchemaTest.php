<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\SqlServerConnection;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class SchemaTest extends TestCase
{
    public function testCreateView()
    {
        Schema::connection('default')->createView('active_users', DB::table('users')->where('active', true));

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithColumns()
    {
        Schema::createView('active_users', 'select id from users where active = 1', ['key']);

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
        $this->assertEquals(1, $users[0]->key);
    }

    public function testCreateViewSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $query = 'create view "active_users" as select * from users where active = 1';
        $bindings = [];
        $builder->getConnection()->expects($this->once())->method('statement')->with($query, $bindings);

        $builder->createView('active_users', 'select * from users where active = 1');
    }

    public function testCreateViewWithBooleanBinding()
    {
        Schema::connection('default')->createView('test', DB::table('users')->where('active', false));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithObjectBinding()
    {
        $object = new class {
            public function __toString()
            {
                return "O'Brien";
            }
        };
        Schema::connection('default')->createView('test', DB::table('users')->where('name', $object));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateViewWithStringBinding()
    {
        Schema::connection('default')->createView('test', DB::table('users')->where('name', "O'Brien"));

        $users = DB::table('test')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateOrReplaceView()
    {
        Schema::createView('active_users', DB::table('users'));

        Schema::createOrReplaceView('active_users', DB::table('users')->where('active', true));

        $users = DB::table('active_users')->get();
        $this->assertCount(1, $users);
    }

    public function testCreateOrReplaceViewSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $builder->getConnection()->expects($this->exactly(2))->method('statement')->withConsecutive(
            ['if exists (select * from sys.objects where type = \'V\' and name = ?) drop view "active_users"', ['active_users']],
            ['create view "active_users" as select * from users where active = 1', []]
        );

        $builder->createOrReplaceView('active_users', 'select * from users where active = 1');
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

    public function testDropViewSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $query = 'drop view "active_users"';
        $bindings = [];
        $builder->getConnection()->expects($this->once())->method('statement')->with($query, $bindings);

        $builder->dropView('active_users');
    }

    public function testDropViewIfExists()
    {
        Schema::dropViewIfExists('active_users');

        $this->assertFalse(Schema::hasView('active_users'));
    }

    public function testDropViewIfExistsSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $query = 'if exists (select * from sys.objects where type = \'V\' and name = ?) drop view "active_users"';
        $bindings = ['active_users'];
        $builder->getConnection()->expects($this->once())->method('statement')->with($query, $bindings);

        $builder->dropViewIfExists('active_users');
    }

    public function testHasView()
    {
        $this->assertFalse(Schema::hasView('active_users'));

        Schema::createView('active_users', DB::table('users')->where('active', true));

        $this->assertTrue(Schema::hasView('active_users'));
    }

    public function testHasViewSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $query = "select * from sys.objects where type = 'V' and name = ?";
        $bindings = ['active_users'];
        $builder->getConnection()->expects($this->once())->method('selectOne')->with($query, $bindings);

        $builder->hasView('active_users');
    }

    protected function getBuilder($database)
    {
        $class = 'Staudenmeir\LaravelMigrationViews\Schema\Builders\\'.$database.'Builder';
        $connection = $this->createMock(SqlServerConnection::class);
        $grammar = 'Staudenmeir\LaravelMigrationViews\Schema\Grammars\\'.$database.'Grammar';
        $connection->method('getSchemaGrammar')->willReturn(new $grammar);

        return new $class($connection);
    }
}
