<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

#[WithMigration]
abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;
    use WithWorkbench;
}
