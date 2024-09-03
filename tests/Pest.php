<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use UseTheFork\Synapse\Tests\TestCase;
  use Saloon\Http\Faking\MockClient;

uses(TestCase::class, RefreshDatabase::class)->beforeEach(fn () => MockClient::destroyGlobal())->in(__DIR__);
