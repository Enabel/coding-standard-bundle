<?php

declare(strict_types=1);

/*
 * This file is part of the EnabelCodingStandardBundle.
 * Copyright (c) Enabel <https://github.com/Enabel>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
