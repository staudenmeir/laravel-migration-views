<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars\Traits;

trait CompilesMySqlViews
{
    /**
     * Compile the query to create a view.
     *
     * @param string $name
     * @param string $query
     * @param list<string|\Illuminate\Database\Query\Expression>|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @param string|null $algorithm
     * @return string
     */
    public function compileCreateView($name, $query, $columns, $orReplace, bool $materialized = false, ?string $algorithm = null)
    {
        $orReplaceSql = $orReplace ? 'or replace ' : '';

        $algorithmSql = $algorithm ? "algorithm = $algorithm " : '';

        $columns = $columns ? '('.$this->columnize($columns).') ' : '';

        return 'create '.$orReplaceSql.$algorithmSql.'view '.$this->wrapTable($name).' '.$columns.'as '.$query;
    }
}
