<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\PostgresGrammar as Base;

class PostgresGrammar extends Base implements ViewGrammar
{
    use CompilesViews;

    /**
     * Compile the query to determine if a materialized view exists.
     *
     * @return string
     */
    public function compileMaterializedViewExists(): string
    {
        return <<<SQL
select exists (
    select 1
    from "pg_matviews"
    where "schemaname" = ? and "matviewname" = ?
)
SQL;
    }
}
