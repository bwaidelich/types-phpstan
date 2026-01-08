<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

interface SomeInterface
{
    public function method(): void;
}

class ClassWithAnonymousClass
{
    public function createAnonymousClass(): SomeInterface
    {
        return new class implements SomeInterface {
            public function method(): void
            {
                // Implementation
            }
        };
    }
}
