<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

trait CompilesViews
{
    /**
     * Compile the query to create a view.
     *
     * @param string $name
     * @param string $query
     * @param array|null $columns
     * @param bool $orReplace
     * @return string
     */
    public function compileCreateView($name, $query, $columns, $orReplace)
    {
        $orReplace = $orReplace ? 'or replace ' : '';

        $columns = $columns ? '('.$this->columnize($columns).') ' : '';

        return 'create '.$orReplace.'view '.$this->wrapTable($name).' '.$columns.'as '.$query;
    }

    /**
     * Compile the query to drop a view.
     *
     * @param string $name
     * @param bool $ifExists
     * @return string
     */
    public function compileDropView($name, $ifExists)
    {
        $ifExists = $ifExists ? 'if exists ' : '';

        return 'drop view '.$ifExists.$this->wrapTable($name);
    }

    /**
     * Compile the query to determine if a view exists.
     *
     * @return string
     */
    public function compileViewExists()
    {
        return 'select * from information_schema.views where table_schema = ? and table_name = ?';
    }
}
