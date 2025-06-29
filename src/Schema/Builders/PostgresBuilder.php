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

    /**
     * Determine if the given materialized view exists.
     *
     * @param string $name
     * @return bool
     */
    public function hasMaterializedView(string $name): bool
    {
        /** @var string $view */
        [$schema, $view] = $this->parseSchemaAndTable($name, true);

        $view = $this->connection->getTablePrefix().$view;

        /** @var \Staudenmeir\LaravelMigrationViews\Schema\Grammars\PostgresGrammar $grammar */
        $grammar = $this->grammar;

        return (bool) $this->connection->scalar(
            $grammar->compileMaterializedViewExists(),
            [$schema, $view]
        );
    }
}
