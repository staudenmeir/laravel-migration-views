<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\PostgresBuilder as Base;

class PostgresBuilder extends Base
{
    use ManagesViews {
        stringifyBindings as parentStringifyBindings;
    }

    /**
     * Stringify the query bindings.
     *
     * @param array $bindings
     * @return array
     */
    protected function stringifyBindings(array $bindings)
    {
        foreach ($bindings as &$binding) {
            if (is_bool($binding)) {
                $binding = $binding ? 'true' : 'false';
            }
        }

        return $this->parentStringifyBindings($bindings);
    }

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
