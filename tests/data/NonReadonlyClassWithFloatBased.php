<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\FloatBased;

#[FloatBased]
final class NonReadonlyClassWithFloatBased
{
    private function __construct(
        public readonly float $value,
    ) {}
}
