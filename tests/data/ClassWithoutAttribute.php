<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

class ClassWithoutAttribute
{
    public function __construct(
        public string $value,
    ) {}
}
