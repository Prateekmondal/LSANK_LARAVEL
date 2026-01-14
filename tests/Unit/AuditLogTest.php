<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogTest extends TestCase
{
    public function test_user_relationship()
    {
        $auditLog = new AuditLog();
        $this->assertTrue(method_exists($auditLog, 'user'));
    }

    public function test_auditable_relationship()
    {
        $auditLog = new AuditLog();
        $this->assertTrue(method_exists($auditLog, 'auditable'));
    }

    public function test_scope_filter_exists()
    {
        $auditLog = new AuditLog();
        $this->assertTrue(method_exists($auditLog, 'scopeFilter'));
    }
}
