<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\StringBased;

#[StringBased]
final class NonReadonlyClassWithStringBased
{
    private function __construct(
        public readonly string $value,
    ) {}
}
