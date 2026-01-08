<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

function testInstantiationOfNormalClass(): void
{
    new ClassWithoutAttribute('test');
}
