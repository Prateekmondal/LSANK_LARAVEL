<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Jobs\ProcessNotification;
use App\Models\User;
use App\Models\ExplosiveChecklist;

class ProcessNotificationTest extends TestCase
{
    public function test_handle_method_exists()
    {
        $job = new ProcessNotification(new User(), new ExplosiveChecklist());
        $this->assertTrue(method_exists($job, 'handle'));
    }
}
