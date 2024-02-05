# Laravel Migration Views

[![CI](https://github.com/staudenmeir/laravel-migration-views/actions/workflows/ci.yml/badge.svg)](https://github.com/staudenmeir/laravel-migration-views/actions/workflows/ci.yml)
[![Code Coverage](https://codecov.io/gh/staudenmeir/laravel-migration-views/graph/badge.svg?token=7YD2SRTL64)](https://codecov.io/gh/staudenmeir/laravel-migration-views)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/staudenmeir/laravel-migration-views/v/stable)](https://packagist.org/packages/staudenmeir/laravel-migration-views)
[![Total Downloads](https://poser.pugx.org/staudenmeir/laravel-migration-views/downloads)](https://packagist.org/packages/staudenmeir/laravel-migration-views/stats)
[![License](https://poser.pugx.org/staudenmeir/laravel-migration-views/license)](https://github.com/staudenmeir/laravel-migration-views/blob/master/LICENSE)

This Laravel extension adds support for SQL views in database migrations.

Supports Laravel 5.5+.
 
## Installation

    composer require staudenmeir/laravel-migration-views:"^1.0"

Use this command if you are in PowerShell on Windows (e.g. in VS Code):

    composer require staudenmeir/laravel-migration-views:"^^^^1.0"

## Versions

| Laravel | Package |
|:--------|:--------|
| 10.x    | 1.7     |
| 9.x     | 1.6     |
| 8.x     | 1.5     |
| 7.x     | 1.4     |
| 6.x     | 1.2     |
| 5.8     | 1.1     |
| 5.5–5.7 | 1.0     |

## Usage

- [Creating Views](#creating-views)
- [Renaming Views](#renaming-views)
- [Dropping Views](#dropping-views)
- [Checking For View Existence](#checking-for-view-existence)
- [Listing View Columns](#listing-view-columns)
- [Materialized Views](#materialized-views)

### Creating Views

Use `createView()` to create a view and provide a query builder instance or an SQL string:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

$query = DB::table('users')->where('active', true);

Schema::createView('active_users', $query);
```

You can provide the view's columns as the third argument:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

$query = 'select id from users where active = 1';

Schema::createView('active_users', $query, ['key']);
```

Use `createOrReplaceView()` to create a view or replace the existing one:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

$query = DB::table('users')->where('active', true); 

Schema::createOrReplaceView('active_users', $query);
```

### Renaming Views

Use `renameView()` to rename a view:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

Schema::renameView('active_users', 'users_active');
```

### Dropping Views

Use `dropView()` or `dropViewIfExists()` to drop a view:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

Schema::dropView('active_users');

Schema::dropViewIfExists('active_users');
```

If you are using `php artisan migrate:fresh`, you can drop all views with `--drop-views` (Laravel 5.6.26+).

### Checking For View Existence

Use `hasView()` to check whether a view exists:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

if (Schema::hasView('active_users')) {
    //
}
```

### Listing View Columns

Use `getViewColumnListing()` to get the column listing for a view:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

$columns = Schema::getViewColumnListing('active_users');
```

### Materialized Views

On PostgreSQL, you can create a materialized view with `createMaterializedView()`:

```php
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

$query = DB::table('users')->where('active', true);

Schema::createMaterializedView('active_users', $query);
```

Use `refreshMaterializedView()` to refresh a materialized view:

```php
Schema::refreshMaterializedView('active_users');
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE OF CONDUCT](.github/CODE_OF_CONDUCT.md) for details.
