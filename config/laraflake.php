<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Snowflake Epoch
    |--------------------------------------------------------------------------
    |
    | This value represents the starting date (epoch) for generating new timestamps.
    | Snowflakes can be created for 69 years past this date. In most cases,
    | you should set this value to the current date when building a new app.
    |
    */
    'epoch' => env('SNOWFLAKE_EPOCH', '2024-01-01'),

    /*
    |--------------------------------------------------------------------------
    | Data Center & Worker IDs
    |--------------------------------------------------------------------------
    |
    | These values represents the data center and worker ids that should be used
    | by Snowflake when generating unique identifiers. The values must be 0--31.
    |
    */
    'datacenter_id' => env('SNOWFLAKE_DATACENTER_ID', 0),
    'worker_id'     => env('SNOWFLAKE_WORKER_ID', 0),
];
