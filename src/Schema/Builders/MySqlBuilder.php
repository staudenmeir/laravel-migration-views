<?php

namespace Staudenmeir\LaravelMigrationViews\Schema\Builders;

use Illuminate\Database\Schema\MySqlBuilder as Base;

class MySqlBuilder extends Base
{
    use ManagesViews;
}
