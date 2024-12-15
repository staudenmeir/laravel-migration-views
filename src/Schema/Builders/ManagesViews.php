<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Support\Str;
use Stringable;

trait ManagesViews
{
    /**
     * Create a new view on the schema.
     *
     * @param string $name
     * @param string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query
     * @param list<string|\Illuminate\Database\Query\Expression>|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @param string|null $algorithm
     * @return void
     */
    public function createView(
        $name,
        $query,
        ?array $columns = null,
        $orReplace = false,
        bool $materialized = false,
        ?string $algorithm = null
    ) {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $query = $this->getQueryString($query);

        $this->connection->statement(
            $grammar->compileCreateView($name, $query, $columns, $orReplace, $materialized, $algorithm)
        );
    }

    /**
     * Create a new view on the schema or replace an existing one.
     *
     * @param string $name
     * @param string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query
     * @param list<string|\Illuminate\Database\Query\Expression>|null $columns
     * @return void
     */
    public function createOrReplaceView($name, $query, ?array $columns = null)
    {
        $this->createView($name, $query, $columns, true);
    }

    /**
     * Create a new materialized view on the schema.
     *
     * @param string $name
     * @param string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query
     * @param list<string|\Illuminate\Database\Query\Expression>|null $columns
     * @return void
     */
    public function createMaterializedView(string $name, $query, ?array $columns = null): void
    {
        $this->createView($name, $query, $columns, materialized: true);
    }

    /**
     * Convert the query and its bindings to an SQL string.
     *
     * @param string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query
     * @return string
     */
    protected function getQueryString($query)
    {
        if (is_string($query)) {
            return $query;
        }

        /** @var array<string, mixed> $bindings */
        $bindings = $query->getBindings();

        $bindings = $this->stringifyBindings($bindings);

        return Str::replaceArray('?', $bindings, $query->toSql());
    }

    /**
     * Stringify the query bindings.
     *
     * @param array<string, mixed> $preparedBindings
     * @return array<string, string>
     */
    protected function stringifyBindings(array $preparedBindings)
    {
        $stringifiedBindings = [];

        /** @var array<string, float|int|string|\Stringable> $preparedBindings */
        $preparedBindings = $this->connection->prepareBindings($preparedBindings);

        foreach ($preparedBindings as $key => $binding) {
            if ($binding instanceof Stringable) {
                $binding = (string) $binding;
            }

            $stringifiedBindings[$key] = $this->connection->getPdo()->quote((string) $binding);
        }

        return $stringifiedBindings;
    }

    /**
     * Rename a view on the schema.
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public function renameView($from, $to)
    {
        $this->rename($from, $to);
    }

    /**
     * Drop a view from the schema.
     *
     * @param string $name
     * @param bool $ifExists
     * @return void
     */
    public function dropView($name, $ifExists = false)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $this->connection->statement(
            $grammar->compileDropView($name, $ifExists)
        );
    }

    /**
     * Drop a view from the schema if it exists.
     *
     * @param string $name
     * @return void
     */
    public function dropViewIfExists($name)
    {
        $this->dropView($name, true);
    }

    /**
     * Get the column listing for a given view.
     *
     * @param string $name
     * @return list<string>
     */
    public function getViewColumnListing($name)
    {
        /** @var list<string> $list */
        $list = $this->getColumnListing($name);

        return $list;
    }

    /**
     * Refresh a materialized view on the schema.
     *
     * @param string $name
     * @return void
     */
    public function refreshMaterializedView(string $name): void
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $this->connection->statement(
            $grammar->compileRefreshMaterializedView($name)
        );
    }
}
