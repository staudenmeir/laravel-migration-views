<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as Base;

class MySqlGrammar extends Base implements ViewGrammar
{
    use CompilesViews;

    protected function compileMaterializedSql(bool $materialized = false)
    {
        return $materialized ? 'ALGORITHM = TEMPTABLE ' : '';
    }
}
