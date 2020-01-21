<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\SqlServerGrammar as Base;

class SqlServerGrammar extends Base
{
    use CompilesViews;

    /**
     * Compile the query to drop a view.
     *
     * @param string $name
     * @param bool $ifExists
     * @return string
     */
    public function compileDropView($name, $ifExists)
    {
        $ifExists = $ifExists ? 'if exists ('.$this->compileViewExists().') ' : '';

        return $ifExists.'drop view '.$this->wrapTable($name);
    }

    /**
     * Compile the query to determine if a view exists.
     *
     * @return string
     */
    public function compileViewExists()
    {
        return "select * from sys.objects where type = 'V' and name = ?";
    }

    /**
     * Compile the query to determine the column listing of a view.
     *
     * @return string
     */
    public function compileViewColumnListing()
    {
        return "select columns.name from sys.columns as columns
                join sys.objects as objects on objects.object_id = columns.object_id
                where objects.type = 'V' and objects.name = ?";
    }
}
