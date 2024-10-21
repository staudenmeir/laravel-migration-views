<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MariaDbGrammar as Base;
use Staudenmeir\LaravelMigrationViews\Schema\Grammars\Traits\CompilesMySqlViews;

class MariaDbGrammar extends Base implements ViewGrammar
{
    use CompilesMySqlViews, CompilesViews {
        CompilesMySqlViews::compileCreateView insteadof CompilesViews;
    }
}
