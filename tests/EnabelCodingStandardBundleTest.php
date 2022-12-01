<?php

declare(strict_types=1);

namespace Enabel\CodingStandardBundle\Tests;

use Enabel\CodingStandardBundle\EnabelCodingStandardBundle;
use PHPUnit\Framework\TestCase;

class EnabelCodingStandardBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        $this->assertSame(\dirname(__DIR__), (new EnabelCodingStandardBundle())->getPath());
    }
}
