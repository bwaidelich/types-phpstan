<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\IntegerBased;

#[IntegerBased]
final readonly class IntegerBasedClass
{
    private function __construct(
        public int $value,
    ) {}
}
