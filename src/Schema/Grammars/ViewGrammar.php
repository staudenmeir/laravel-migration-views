<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

interface ViewGrammar
{
    /**
     * Compile the query to create a view.
     *
     * @param string $name
     * @param string $query
     * @param list<string|\Illuminate\Database\Query\Expression<*>>|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @param string|null $algorithm
     * @return string
     */
    public function compileCreateView($name, $query, $columns, $orReplace, bool $materialized = false, ?string $algorithm = null);

    /**
     * Compile the query to drop a view.
     *
     * @param string $name
     * @param bool $ifExists
     * @param bool $materialized
     * @return string
     */
    public function compileDropView($name, $ifExists, bool $materialized = false);

    /**
     * Compile the query to refresh a materialized view.
     *
     * @param string $name
     * @return string
     */
    public function compileRefreshMaterializedView(string $name): string;
}
