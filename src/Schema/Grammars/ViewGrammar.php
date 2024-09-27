<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

interface ViewGrammar
{
    /**
     * Compile the query to create a view.
     *
     * @param string $name
     * @param string $query
     * @param array|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @return string
     */
    public function compileCreateView($name, $query, $columns, $orReplace, bool $materialized = false);

    /**
     * Compile the query to drop a view.
     *
     * @param string $name
     * @param bool $ifExists
     * @return string
     */
    public function compileDropView($name, $ifExists);

    /**
     * Compile the query to refresh a materialized view.
     *
     * @param string $name
     * @return string
     */
    public function compileRefreshMaterializedView(string $name): string;
}
