<?php

namespace AfriCC\Tests\Integration\Pdns\Types;

use AfriCC\Pdns\Types\A;
use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    public function testA()
    {
        $type = new A('test.localdomain', '127.0.0.9', 300);
        $this->assertEquals([
            'name' => 'test.localdomain.',
            'type' => 'A',
            'ttl' => 300,
            'records' => [
                0 => [
                    'content' => '127.0.0.9',
                    'disabled' => false,
                ],
            ],
        ], $type->payload());
    }
}
