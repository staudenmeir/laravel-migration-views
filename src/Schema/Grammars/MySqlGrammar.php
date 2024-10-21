<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as Base;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\Traits\CompilesMySqlViews;

class MySqlGrammar extends Base implements ViewGrammar
{
    use CompilesMySqlViews, CompilesViews {
        CompilesMySqlViews::compileCreateView insteadof CompilesViews;
    }
}
