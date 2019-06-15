<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\SqlServerBuilder as Base;

class SqlServerBuilder extends Base
{
    use ManagesViews {
        createView as createViewParent;
    }

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
        if ($orReplace) {
            $this->dropViewIfExists($name);
        }

        $this->createViewParent($name, $query, $columns);
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
            $this->grammar->compileDropView($name, $ifExists),
            $ifExists ? [$this->connection->getTablePrefix().$name] : []
        );
    }

    /**
     * Get the bindings for a "Has View" statement.
     *
     * @param string $view
     * @return array
     */
    protected function getBindingsForHasView($view)
    {
        return [$view];
    }
}
