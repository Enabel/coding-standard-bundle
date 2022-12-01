<?php

declare(strict_types=1);

namespace Enabel\CodingStandardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnabelCodingStandardBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
