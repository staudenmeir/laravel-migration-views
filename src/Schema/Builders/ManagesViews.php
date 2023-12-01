<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

trait ManagesViews
{
    /**
     * Create a new view on the schema.
     *
     * @param string $name
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|string $query
     * @param array|null $columns
     * @param bool $orReplace
     * @return void
     */
    public function createView($name, $query, array $columns = null, $orReplace = false)
    {
        $query = $this->getQueryString($query);

        $this->connection->statement(
            $this->grammar->compileCreateView($name, $query, $columns, $orReplace)
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
    public function createOrReplaceView($name, $query, array $columns = null)
    {
        $this->createView($name, $query, $columns, true);
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

        return $query->toRawSql();
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

        foreach ($bindings as &$binding) {
            if (is_object($binding)) {
                $binding = (string) $binding;
            }

            if (is_string($binding)) {
                $binding = $this->connection->getPdo()->quote($binding);
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
        $this->connection->statement(
            $this->grammar->compileDropView($name, $ifExists)
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
     * Determine if the given view exists on the schema.
     *
     * @param string $name
     * @return bool
     */
    public function hasView($name)
    {
        $view = $this->connection->getTablePrefix().$name;

        $view = $this->connection->selectOne(
            $this->grammar->compileViewExists(),
            $this->getBindingsForHasView($view)
        );

        return !is_null($view);
    }

    /**
     * Get the bindings for a "Has View" statement.
     *
     * @param string $view
     * @return array
     */
    protected function getBindingsForHasView($view)
    {
        return [$this->connection->getDatabaseName(), $view];
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
}
