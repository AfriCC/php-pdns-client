<?php

namespace AfriCC\Tests\Unit\Pdns;

use AfriCC\Pdns\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testClassName()
    {
        $this->assertEquals('HelperTest', Helper::className($this));
    }

    public function testCanonical()
    {
        $this->assertEquals('root.', Helper::canonical('root'));
    }
}
