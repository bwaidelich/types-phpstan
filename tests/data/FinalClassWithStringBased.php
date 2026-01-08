<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\StringBased;

#[StringBased]
final readonly class FinalClassWithStringBased
{
    private function __construct(
        public string $value,
    ) {}
}
