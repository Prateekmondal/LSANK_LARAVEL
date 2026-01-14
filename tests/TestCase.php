<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    // Add the following setUp method
    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('Field_Officer', 'web');
    }
}
