<?php

namespace AfriCC\Tests\Integration\Pdns\Endpoints;

use AfriCC\Pdns\Endpoints\Statistics;
use PHPUnit\Framework\TestCase;

class StatisticsTest extends TestCase
{
    public function testAll()
    {
        $statistics = new Statistics;
        $statistics->all();

        $this->assertEquals('/api/v1/servers/localhost/statistics', $statistics->getUri());
        $this->assertEquals('GET', $statistics->getMethod());
        $this->assertFalse($statistics->hasPayload());
    }
}
