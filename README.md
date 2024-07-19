# Laraflake

This package enables a Laravel application to create [Snowflake IDs](https://en.wikipedia.org/wiki/Snowflake_ID).
It is a very thin wrapper around the excellent [godruoyi/php-snowflake](https://github.com/godruoyi/php-snowflake) library.

## What are Snowflakes?

Snowflakes are a form of unique identifier devised by X/Twitter and are used by many companies, including Instagram and Discord, to generate unique IDs for their entities.

Some of the benefits of using Snowflakes (over alternatives such as UUID/ULID) include:

- They consist entirely of integers.
- They use less space (16 characters, so it fits in a `BIGINT`).
- Indexing of integers is much faster than indexing a string.
- Keys begin with a timestamp, so are sortable.
- Keys end with a random number, so guessing table size is not possible.
- Databases handle integers more efficiently than strings.
- Generation of new keys is faster (less than 1 ms).

## Installation

Pull in the package using Composer:

```bash
composer require calebdw/laraflake
```

## Configuration

Snowflake includes a configuration file with several settings that you can use to initialize the Snowflake service.
You should begin by publishing this configuration file:

```bash
php artisan vendor:publish
```

### Snowflake Epoch

The 41-bit timestamp encoded in the Snowflake is the difference between the time of creation and a given starting epoch/timestamp.
Snowflakes can be generated for up to 69 years past the given epoch.

The default epoch is `2024-01-01`, but in most cases you should set this value to the current date using a format of `YYYY-MM-DD`.

> **Note**:
> Do not set the timestamp to a date in the future, as that won't achieve anything.
> You should also avoid using a date far in the past (such as the Unix epoch `1970-01-01`), as that may reduce the number of years for which you can generate timestamps.

### Data Center & Worker IDs

If using a distributed architectural setup, you'll need to set the data center and worker IDs that the application should use when generating Snowflakes.
These are both set to `0` by default, as that is a good starting point, but you are free to increase these numbers as you add more workers and data centers.

The maximums for each of these configuration values is `31`. This gives you up to 32 workers per data center, and 32 data centers in total.
Therefore, you can have up `1024` workers each generating unique Snowflakes.

### Sequence resolver

In order to handle the generation of unique keys within the same millisecond, the service uses a sequence resolver.
There are several to choose from, however they each have dependencies, such as Redis.
You are free to use any of them, however the default option is a good choice, as it **doesn't** have any dependencies.

## Usage

> **WARNING**: Do not create new instances of the Snowflake service, as doing so risks generating matching keys / introducing collisions.
> Instead, always resolve the Snowflake singleton out of the container. You can also use the global helper method (see below).


You can generate a Snowflake by resolving the service out of the container and calling its `id` method:
Since this is a little cumbersome, the package also registers a global `snowflake()` helper method that you can use anywhere.

```php
<?php

declare(strict_types=1);
use CalebDW\Laraflake\Facades\Snowflake;
use Illuminate\Support\Str;

resolve('snowflake')->id(); // (string) "5585066784854016"
snowflake()->id(); // (string) "5585066784854016"
Snowflake::id(); // (string) "5585066784854016"
Str::snowflakeId(); // (string) "5585066784854016"
```

## Databases

If you want to use Snowflakes in your database e.g. for primary and foreign keys, then you'll need to perform a couple of steps.

First, modify your migrations so that they use the Snowflake migration methods e.g.

```php
<?php

declare(strict_types=1);

// Before
$table->id();
$table->foreignId('user_id');
$table->foreignIdFor(User::class);

// After
$table->snowflake()->primary();
$table->foreignSnowflake('user_id');
$table->foreignSnowflakeFor(User::class);
```

Here's an example:

```php
<?php

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function(Blueprint $table) {
            $table->snowflake()->primary();
            $table->foreignSnowflake('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 100);
            $table->timestamps();
        });
    }
}
```

Next, if you're using Eloquent, add the package's `HasSnowflakes` trait to your Eloquent models:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;

class Post extends Model
{
    use HasSnowflakes;
}
```

Finally, configure the model's `$casts` array to use the package's `AsSnowflake` for all Snowflake attributes.
This cast automatically handles conversion from the database integer to a string representation in the application.
It also ensures that languages which do not support 64-bit integers (such as JavaScript), will not truncate the Snowflake.

```php
<?php

declare(strict_types=1);

namespace App\Models;

use CalebDW\Laraflake\Casts\AsSnowflake;
use CalebDW\Laraflake\Concerns\HasSnowflakes;

class Post extends Model
{
    use HasSnowflakes;

    protected $casts = [
        'id'      => AsSnowflake::class,
        'user_id' => AsSnowflake::class,
        'title'   => 'string',
    ];
}
```

## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

Laraflake is open-sourced software licensed under the [MIT license](LICENSE).

## Acknowledgements

Derived from [caneara/snowflake](https://github.com/caneara/snowflake) which is no longer maintained.
