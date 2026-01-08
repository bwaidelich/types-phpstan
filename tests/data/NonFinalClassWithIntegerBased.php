<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\IntegerBased;

#[IntegerBased]
readonly class NonFinalClassWithIntegerBased
{
    private function __construct(
        public int $value,
    ) {}
}
