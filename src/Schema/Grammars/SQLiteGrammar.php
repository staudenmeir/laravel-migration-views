<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\SQLiteGrammar as Base;

class SQLiteGrammar extends Base implements ViewGrammar
{
    use CompilesViews;
}
