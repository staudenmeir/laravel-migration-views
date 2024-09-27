<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\SQLiteBuilder as Base;
use Illuminate\Support\Str;

class SQLiteBuilder extends Base
{
    use ManagesViews {
        createView as createViewParent;
    }

    /** @inheritDoc */
    public function createView($name, $query, ?array $columns = null, $orReplace = false, bool $materialized = false)
    {
        if ($orReplace) {
            $this->dropViewIfExists($name);
        }

        $this->createViewParent($name, $query, $columns);
    }

    /** @inheritDoc */
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
