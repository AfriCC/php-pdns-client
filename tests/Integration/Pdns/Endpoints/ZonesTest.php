<?php

namespace AfriCC\Tests\Integration\Pdns\Endpoints;

use AfriCC\Pdns\Endpoints\Zones;
use AfriCC\Pdns\Types\A;
use PHPUnit\Framework\TestCase;

class ZonesTest extends TestCase
{
    public function testUpsertRecord()
    {
        $zones = new Zones;
        $zones->upsertRecord('bar.localdomain', new A('foo.bar.localdomain', '127.0.0.9', 300));

        $this->assertEquals('/api/v1/servers/localhost/zones/bar.localdomain.', $zones->getUri());
        $this->assertEquals('PATCH', $zones->getMethod());
        $this->assertTrue($zones->hasPayload());
        $this->assertJsonStringEqualsJsonString(
            '{"rrsets":[{"name":"foo.bar.localdomain.","type":"A","ttl":300,"records":[{"content":"127.0.0.9","disabled":false}],"changetype":"REPLACE"}]}',
            $zones->getPayload()
        );
    }
}
