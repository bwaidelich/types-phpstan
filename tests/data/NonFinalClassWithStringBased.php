<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\StringBased;

#[StringBased]
readonly class NonFinalClassWithStringBased
{
    private function __construct(
        public string $value,
    ) {}
}
