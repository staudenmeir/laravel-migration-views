<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MariaDbGrammar as Base;

class MariaDbGrammar extends Base implements ViewGrammar
{
    use CompilesViews;
}
