<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

trait ManagesViews
{
    /**
     * Create a new view on the schema.
     *
     * @param string $name
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|string $query
     * @param array|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @return void
     */
    public function createView($name, $query, ?array $columns = null, $orReplace = false, bool $materialized = false)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $query = $this->getQueryString($query);

        $this->connection->statement(
            $grammar->compileCreateView($name, $query, $columns, $orReplace, $materialized)
        );
    }

    /**
     * Create a new view on the schema or replace an existing one.
     *
     * @param string $name
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|string $query
     * @param array|null $columns
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
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|string $query
     * @param array|null $columns
     * @return void
     */
    public function createMaterializedView(string $name, $query, ?array $columns = null): void
    {
        $this->createView($name, $query, $columns, materialized: true);
    }

    /**
     * Convert the query and its bindings to an SQL string.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|string $query
     * @return string
     */
    protected function getQueryString($query)
    {
        if (is_string($query)) {
            return $query;
        }

        $bindings = $this->stringifyBindings(
            $query->getBindings()
        );

        return Str::replaceArray('?', $bindings, $query->toSql());
    }

    /**
     * Stringify the query bindings.
     *
     * @param array $bindings
     * @return array
     */
    protected function stringifyBindings(array $bindings)
    {
        $bindings = $this->connection->prepareBindings($bindings);

        foreach ($bindings as $key => $binding) {
            if (is_object($binding)) {
                $binding = (string) $binding;
            }

            if (is_string($binding)) {
                $bindings[$key] = $this->connection->getPdo()->quote($binding);
            }
        }

        return $bindings;
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
     * @return array
     */
    public function getViewColumnListing($name)
    {
        return $this->getColumnListing($name);
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
