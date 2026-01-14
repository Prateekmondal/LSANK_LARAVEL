<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Notifications\ExternalSignerNotification;
use App\Models\ExplosiveChecklist;

class ExternalSignerNotificationTest extends TestCase
{
    public function test_via_method_exists()
    {
        $notification = new ExternalSignerNotification(new ExplosiveChecklist());
        $this->assertTrue(method_exists($notification, 'via'));
    }

    public function test_toMail_method_exists()
    {
        $notification = new ExternalSignerNotification(new ExplosiveChecklist());
        $this->assertTrue(method_exists($notification, 'toMail'));
    }
}
