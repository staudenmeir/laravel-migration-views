<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\SqlServerBuilder as Base;

class SqlServerBuilder extends Base
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
    public function dropView($name, $ifExists = false)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\ViewGrammar $grammar */
        $grammar = $this->grammar;

        $this->connection->statement(
            $grammar->compileDropView($name, $ifExists),
            $ifExists ? [$this->connection->getTablePrefix().$name] : []
        );
    }

    /** @inheritDoc */
    public function getViewColumnListing($name)
    {
        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\SqlServerGrammar $grammar */
        $grammar = $this->grammar;

        $results = $this->connection->selectFromWriteConnection(
            $grammar->compileViewColumnListing(),
            [$this->connection->getTablePrefix().$name]
        );

        return array_map(fn ($result) => ((object) $result)->name, $results);
    }
}
