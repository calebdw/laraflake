<?php

declare(strict_types=1);

namespace CalebDW\Laraflake;

use CalebDW\Laraflake\Mixins\BlueprintMixin;
use CalebDW\Laraflake\Mixins\RuleMixin;
use CalebDW\Laraflake\Mixins\StrMixin;
use Composer\InstalledVersions;
use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceProvider extends IlluminateServiceProvider
{
    /** @inheritDoc */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laraflake.php', 'laraflake');

        $this->registerSequenceResolver();
        $this->registerSnowflake();
    }

    /** @inheritDoc */
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/laraflake.php' => config_path('laraflake.php')]);

        $this->registerMixins();

        AboutCommand::add('Laraflake', fn () => [
            'Version' => InstalledVersions::getPrettyVersion('calebdw/laraflake'),
        ]);
    }

    /** Register custom mixins. */
    protected function registerMixins(): void
    {
        Blueprint::mixin(new BlueprintMixin());
        Str::mixin(new StrMixin());
        Rule::mixin(new RuleMixin());
    }

    /** Register the Snowflake singleton. */
    protected function registerSnowflake(): void
    {
        $this->app->singleton(Snowflake::class, function ($app) {
            /** @var array{datacenter_id: int, worker_id: int, epoch: string} $config */
            $config = config('laraflake');

            return (new Snowflake($config['datacenter_id'], $config['worker_id']))
                ->setStartTimeStamp(strtotime($config['epoch']) * 1000)
                ->setSequenceResolver($app->make(SequenceResolver::class));
        });
        $this->app->alias(Snowflake::class, 'laraflake');
    }

    /** Bind the Snowflake sequence resolver. */
    protected function registerSequenceResolver(): void
    {
        $this->app->bind(SequenceResolver::class, function ($app) {
            if (! $app->has('cache.store')) {
                return new RandomSequenceResolver();
            }

            $repository = $app->get('cache.store');
            assert($repository instanceof Repository);

            return new LaravelSequenceResolver($repository);
        });
    }
}
