<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Mail\ContactMail;

class ContactMailTest extends TestCase
{
    public function test_build_method_exists()
    {
        $mail = new ContactMail([]);
        $this->assertTrue(method_exists($mail, 'build'));
    }
}
