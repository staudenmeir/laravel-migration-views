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
     * @param string|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Query\Builder $query
     * @param list<string|\Illuminate\Database\Query\Expression<*>>|null $columns
     * @param bool $orReplace
     * @param bool $materialized
     * @return void
     */
    public function createView($name, $query, ?array $columns = null, $orReplace = false, bool $materialized = false)
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
     * @param bool $materialized
     * @return void
     */
    public function dropView($name, $ifExists = false, bool $materialized = false)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $this->connection->statement(
            $grammar->compileDropView($name, $ifExists),
            $ifExists ? [$this->connection->getTablePrefix().$name] : []
        );
    }

    /**
     * Get the column listing for a given view.
     *
     * @param string $name
     * @return list<string>
     */
    public function getViewColumnListing($name)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\SqlServerGrammar $grammar */
        $grammar = $this->grammar;

        /** @var list<array{name: string}> $results */
        $results = $this->connection->selectFromWriteConnection(
            $grammar->compileViewColumnListing(),
            [$this->connection->getTablePrefix().$name]
        );

        return array_map(fn ($result) => ((object) $result)->name, $results);
    }
}
