<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\PostgresGrammar as Base;

class PostgresGrammar extends Base implements ViewGrammar
{
    use CompilesViews;
}
