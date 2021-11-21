<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\PostgresBuilder as Base;

class PostgresBuilder extends Base
{
    use ManagesViews;

    /**
     * Get the bindings for a "Has View" statement.
     *
     * @param string $view
     * @return array
     */
    protected function getBindingsForHasView($view)
    {
        [, $schema, $view] = $this->parseSchemaAndTable($view);

        return [$schema, $view];
    }
}
