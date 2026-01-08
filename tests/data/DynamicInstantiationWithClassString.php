<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

function testDynamicInstantiation(): void
{
    $className = FinalClassWithStringBased::class;
    new $className();
}
