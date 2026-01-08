<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\IntegerBased;

#[IntegerBased]
final readonly class ClassWithProtectedConstructor
{
    protected function __construct(
        public int $value,
    ) {}
}
