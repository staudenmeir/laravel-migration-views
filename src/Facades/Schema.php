<?php

namespace Staudenmeir\LaravelMigrationViews\Facades;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Schema as Base;
use RuntimeException;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\MySqlBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\PostgresBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\SQLiteBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Builders\SqlServerBuilder;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\MySqlGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\PostgresGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\SQLiteGrammar;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\SqlServerGrammar;

/**
 * @method static void createView(string $name, $query, array $columns = null, bool $orReplace = false)
 * @method static void createOrReplaceView(string $name, $query, array $columns = null)
 * @method static void renameView(string $from, string $to)
 * @method static void dropView(string $name, bool $ifExists = false)
 * @method static void dropViewIfExists(string $name)
 * @method static bool hasView(string $name)
 */
class Schema extends Base
{
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
     * Get a schema builder instance for the default connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function getFacadeAccessor()
    {
        return static::getSchemaBuilder(
            static::$app['db']->connection()
        );
    }

    /**
     * Get the schema builder.
     *
     * @param \Illuminate\Database\Connection $connection
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function getSchemaBuilder(Connection $connection)
    {
        $driver = $connection->getDriverName();

        switch ($driver) {
            case 'mariadb':
            case 'mysql':
                $connection->setSchemaGrammar($connection->withTablePrefix(new MySqlGrammar));

                return new MySqlBuilder($connection);
            case 'pgsql':
                $connection->setSchemaGrammar($connection->withTablePrefix(new PostgresGrammar));

                return new PostgresBuilder($connection);
            case 'sqlite':
                $connection->setSchemaGrammar($connection->withTablePrefix(new SQLiteGrammar));

                return new SQLiteBuilder($connection);
            case 'sqlsrv':
                $connection->setSchemaGrammar($connection->withTablePrefix(new SqlServerGrammar));

                return new SqlServerBuilder($connection);
        }

        throw new RuntimeException('This database is not supported.'); // @codeCoverageIgnore
    }
}
