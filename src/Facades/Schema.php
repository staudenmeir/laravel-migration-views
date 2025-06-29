<?php

namespace Staudenmeir\LaravelMigrationViews\Facades;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Facade;
use RuntimeException;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\MariaDbBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\MySqlBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\PostgresBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\SQLiteBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\SqlServerBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\MariaDbGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\MySqlGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\PostgresGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\SQLiteGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\SqlServerGrammar;

/**
 * @method static void createView(string $name, string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query, list<string|\Illuminate\Database\Query\Expression<*>>|null $columns = null, bool $orReplace = false, ?string $algorithm = null)
 * @method static void createOrReplaceView(string $name, string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query, list<string|\Illuminate\Database\Query\Expression<*>>|null $columns = null)
 * @method static void createMaterializedView(string $name, string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query, list<string|\Illuminate\Database\Query\Expression<*>>|null $columns = null)
 * @method static void renameView(string $from, string $to)
 * @method static void dropView(string $name, bool $ifExists = false)
 * @method static void dropViewIfExists(string $name)
 * @method static void dropMaterializedView(string $name)
 * @method static void dropMaterializedViewIfExists(string $name)
 * @method static list<string> getViewColumnListing(string $name)
 * @method static void refreshMaterializedView(string $name)
 * @method static bool hasMaterializedView(string $name)
 *
 * @mixin \Illuminate\Support\Facades\Schema
 */
class Schema extends Facade
{
    /** @inheritDoc */
    protected static function getFacadeAccessor()
    {
        return static::class;
    }

    /**
     * Get a schema builder instance for a connection.
     *
     * @param string $name
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function connection($name)
    {
        /** @var array{db: \Illuminate\Database\DatabaseManager} $app */
        $app = static::$app;

        return static::getSchemaBuilder(
            $app['db']->connection($name)
        );
    }

    /**
     * Get the schema builder.
     *
     * @param \Illuminate\Database\Connection $connection
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function getSchemaBuilder(Connection $connection)
    {
        return match ($connection->getDriverName()) {
            'mysql' => new MySqlBuilder(
                $connection->setSchemaGrammar(
                    $connection->withTablePrefix(new MySqlGrammar())
                )
            ),
            'mariadb' => new MariaDbBuilder(
                $connection->setSchemaGrammar(
                    $connection->withTablePrefix(new MariaDbGrammar())
                )
            ),
            'pgsql' => new PostgresBuilder(
                $connection->setSchemaGrammar(
                    $connection->withTablePrefix(new PostgresGrammar())
                )
            ),
            'sqlite' => new SQLiteBuilder(
                $connection->setSchemaGrammar(
                    $connection->withTablePrefix(new SQLiteGrammar())
                )
            ),
            'sqlsrv' => new SqlServerBuilder(
                $connection->setSchemaGrammar(
                    $connection->withTablePrefix(new SqlServerGrammar())
                )
            ),
            default => throw new RuntimeException('This database is not supported.'), // @codeCoverageIgnore
        };
    }
}
