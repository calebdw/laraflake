<p align="center">
  <img src="/art/laraflake.webp" alt="Laraflake" width="35%">
</p>
<p align="center">Generate X/Twitter <a href="https://en.wikipedia.org/wiki/Snowflake_ID">Snowflake identifiers</a> in Laravel.</p>
<p align="center">
  <a href="https://github.com/calebdw/laraflake/actions/workflows/tests.yml">
    <img src="https://github.com/calebdw/laraflake/actions/workflows/tests.yml/badge.svg" alt="Test Results">
  </a>
  <a href="https://codecov.io/github/calebdw/laraflake" >
    <img src="https://codecov.io/github/calebdw/laraflake/graph/badge.svg?token=RPLQKWDM5G" alt="Code Coverage">
  </a>
  <a href="https://github.com/calebdw/laraflake">
    <img src="https://img.shields.io/github/license/calebdw/laraflake" alt="License">
  </a>
  <a href="https://packagist.org/packages/calebdw/laraflake">
    <img src="https://img.shields.io/packagist/v/calebdw/laraflake.svg" alt="Packagist Version">
  </a>
</p>

## What are Snowflakes?

Snowflakes are a form of unique identifier [devised by X/Twitter](https://blog.x.com/engineering/en_us/a/2010/announcing-snowflake) and are used by many companies, including Instagram and Discord, to generate unique IDs for their entities.

Some of the benefits of using Snowflakes (over alternatives such as UUID/ULID) include:

- **Timestamp Component:** Extract creation time directly from the ID.
- **Uniqueness Across Distributed Systems:** Ensures unique IDs without coordination.
- **Orderability:** Roughly ordered by creation time for easy sorting.
- **Compactness:** 64-bit size, more compact than 128-bit UUIDs.
- **Performance:** Faster and less resource-intensive generation.
- **Configurability:** Flexible bit allocation for specific needs.
- **Storage Efficiency:** More efficient storage compared to larger identifiers.
- **Database Indexing:** Faster indexing and query performance.
- **Human Readability:** More compact and readable than longer identifiers.

## Installation

First pull in the package using Composer:

```bash
composer require calebdw/laraflake
```

And then publish the package's configuration file:

```bash
php artisan vendor:publish --provider="CalebDW\Laraflake\ServiceProvider"
```

## Configuration

### Snowflake Epoch

The 41-bit timestamp encoded in the Snowflake is the difference between the time of creation and a given starting epoch/timestamp.
Snowflakes can be generated for up to 69 years past the given epoch.
In most cases you should set this value to the current date using a format of `YYYY-MM-DD`.

> **Note**:
> Future dates will throw an error and you should avoid using a date far in the past (such as the Unix epoch `1970-01-01`)
as that may reduce the number of years for which you can generate timestamps.

### Data Center & Worker IDs

If using distributed systems, you'll need to set the data center and worker IDs that the application should use when generating Snowflakes.
These are used to ensure that each worker generates unique Snowflakes and can range from `0` to `31` (up to `1024` unique workers).

## Usage

> **WARNING**: Do not create new instances of the Snowflake generator (as this could cause collisions), always use the Snowflake singleton from the container.

You can generate a Snowflake by resolving the singleton from the container and calling its `id` method:

```php
use Godruoyi\Snowflake\Snowflake;

resolve('snowflake')->id();      // (string) "5585066784854016"
resolve(Snowflake::class)->id(); // (string) "5585066784854016"
```
This package also provides a `snowflake` helper function, a `Snowflake` facade, and a `Str::snowflakeId` macro for convenience:

```php
use CalebDW\Laraflake\Facades\Snowflake;
use Illuminate\Support\Str;

snowflake()->id();  // (string) "5585066784854016"
Snowflake::id();    // (string) "5585066784854016"
Str::snowflakeId(); // (string) "5585066784854016"
```

### Eloquent Integration

#### Migrations

This package provides a set of migration macros to make it easier to work with Snowflakes in your database schema.

Here's an example:

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function(Blueprint $table) {
            $table->snowflake()->primary();
            $table->foreignSnowflake('user_id')->constrained()->cascadeOnDelete();
            $table->foreignSnowflakeFor(Post::class)->constrained();
        });
    }
}
```

#### Models

Next, add the package's `HasSnowflakes` trait to your Eloquent models:

```php
namespace App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;

class Post extends Model
{
    use HasSnowflakes;
}
```

The trait provides several features for the model's Snowflake columns:
- the generation of Snowflakes for new records
- route model binding
- automatic casting from database integers to strings which prevents truncation in languages that do not support 64-bit integers (such as JavaScript).

By default, the trait assumes that the model's primary key is a Snowflake.
If you have other unique columns that should be treated as Snowflakes, you can override the `uniqueIds` method to specify them:

```php

namespace App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;

class Post extends Model
{
    use HasSnowflakes;

    /** @inheritDoc */
    public function uniqueIds(): array
    {
        return [$this->getKeyName(), 'slug'];
    }
}
```

If necessary, you can explicitly cast the model's Snowflake columns using the `AsSnowflake` cast:

```php
namespace App\Models;

use CalebDW\Laraflake\Casts\AsSnowflake;
use CalebDW\Laraflake\Concerns\HasSnowflakes;

class Post extends Model
{
    use HasSnowflakes;

    protected $casts = [
        'id'      => AsSnowflake::class,
        'user_id' => AsSnowflake::class,
    ];
}
```

### Validation

If you need to validate Snowflakes in your application, you can use the `Snowflake` rule or the `Rule::snowflake` macro provided by this package:

```php
use CalebDW\Laraflake\Rules\Snowflake;
use Illuminate\Validation\Rule;

$request->validate([
    'id'      => ['required', new Snowflake()],
    'user_id' => ['required', Rule::snowflake()],
]);
```

You can also just use the `Str` macro to check if a value is a valid Snowflake:

```php
use Illuminate\Support\Str;

Str::isSnowflake('5585066784854016'); // (bool) true
```

### Sequence Resolver

The sequence resolver is responsible for generating the sequence component of the Snowflake
to ensure that numbers generated on the same machine within the same millisecond are unique.

By default, if the application has a cache, then it uses the `LaravelSequenceResolver`
which uses the Laravel cache to store the last sequence number.

If the application does not have a cache, then it uses the `RandomSequenceResolver` which
has no dependencies **but is not concurrency-safe**.

You can override the sequence resolver by binding your own implementation in a service provider:

```php
use Godruoyi\Snowflake\SequenceResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SequenceResolver::class, function() {
            return new MySequenceResolver();
        });
    }
}
```

Please see [godruoyi/php-snowflake](https://github.com/godruoyi/php-snowflake) for more information on the available sequence resolvers and their dependencies.

## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

Laraflake is open-sourced software licensed under the [MIT license](LICENSE).

## Acknowledgements

Derived from [caneara/snowflake](https://github.com/caneara/snowflake) which is no longer maintained.
The actual Snowflake generation is handled by the excellent [godruoyi/php-snowflake](https://github.com/godruoyi/php-snowflake) library.
