![CI](https://github.com/staudenmeir/laravel-migration-views/workflows/CI/badge.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/laravel-migration-views/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/staudenmeir/laravel-migration-views/v/stable)](https://packagist.org/packages/staudenmeir/laravel-migration-views)
[![Total Downloads](https://poser.pugx.org/staudenmeir/laravel-migration-views/downloads)](https://packagist.org/packages/staudenmeir/laravel-migration-views)
[![License](https://poser.pugx.org/staudenmeir/laravel-migration-views/license)](https://packagist.org/packages/staudenmeir/laravel-migration-views)

## Introduction
This Laravel extension adds support for SQL views in database migrations.

Supports Laravel 5.5.25+.
 
## Installation

    composer require staudenmeir/laravel-migration-views:"^1.0"

Use this command if you are in PowerShell on Windows (e.g. in VS Code):

    composer require staudenmeir/laravel-migration-views:"^^^^1.0"

## Usage

- [Creating Views](#creating-views)
- [Renaming Views](#renaming-views)
- [Dropping Views](#dropping-views)
- [Checking For View Existence](#checking-for-view-existence)
- [Listing View Columns](#listing-view-columns)

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

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE OF CONDUCT](.github/CODE_OF_CONDUCT.md) for details.
