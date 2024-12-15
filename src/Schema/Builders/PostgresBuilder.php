<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\PostgresBuilder as Base;

class PostgresBuilder extends Base
{
    use ManagesViews {
        stringifyBindings as baseStringifyBindings;
    }

    /**
     * Stringify the query bindings.
     *
     * @param array<string, mixed> $bindings
     * @return array<string, string>
     */
    protected function stringifyBindings(array $bindings)
    {
        foreach ($bindings as $key => $binding) {
            if (is_bool($binding)) {
                $bindings[$key] = $binding ? 'true' : 'false';
            }
        }

        return $this->baseStringifyBindings($bindings);
    }
}
