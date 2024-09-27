<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\PostgresBuilder as Base;

class PostgresBuilder extends Base
{
    use ManagesViews {
        stringifyBindings as baseStringifyBindings;
    }

    /** @inheritDoc */
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
