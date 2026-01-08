<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Attributes\ListBased;

#[ListBased(itemClassName: \stdClass::class)]
final readonly class ClassWithoutExplicitConstructor
{
    // No explicit constructor - this is valid
}
