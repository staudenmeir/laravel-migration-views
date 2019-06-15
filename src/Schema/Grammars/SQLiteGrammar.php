<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\SQLiteGrammar as Base;

class SQLiteGrammar extends Base
{
    use CompilesViews;

    /**
     * Compile the query to determine if a view exists.
     *
     * @return string
     */
    public function compileViewExists()
    {
        return "select * from sqlite_master where type = 'view' and name = ?";
    }
}
