<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\SQLiteBuilder as Base;
use Illuminate\Support\Str;

class SQLiteBuilder extends Base
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
     * @param bool $materialized
     * @return void
     */
    public function createView($name, $query, array $columns = null, $orReplace = false, bool $materialized = false)
    {
        if ($orReplace) {
            $this->dropViewIfExists($name);
        }

        $this->createViewParent($name, $query, $columns);
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
        $view = $this->connection->selectOne(
            "select * from sqlite_master where type = 'view' and name = ?",
            [$this->connection->getTablePrefix().$from]
        );

        $this->dropView($from);

        $query = Str::replaceFirst(
            $this->grammar->wrapTable($from),
            $this->grammar->wrapTable($to),
            $view->sql
        );

        $this->connection->statement($query);
    }
}
