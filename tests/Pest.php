<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Saloon\Http\Faking\MockClient;
use UseTheFork\Synapse\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->beforeEach(fn () => MockClient::destroyGlobal())->in(__DIR__);
