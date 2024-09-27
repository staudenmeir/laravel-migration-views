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
 * @method static void createView(string $name, $query, array $columns = null, bool $orReplace = false)
 * @method static void createOrReplaceView(string $name, $query, array $columns = null)
 * @method static void createMaterializedView(string $name, $query, array $columns = null)
 * @method static void renameView(string $from, string $to)
 * @method static void dropView(string $name, bool $ifExists = false)
 * @method static void dropViewIfExists(string $name)
 * @method static array getViewColumnListing(string $name)
 * @method static void refreshMaterializedView(string $name)
 *
 * @mixin \Illuminate\Support\Facades\Schema
 */
class Schema extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
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
        return static::getSchemaBuilder(
            static::$app['db']->connection($name)
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
