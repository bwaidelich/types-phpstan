<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

function testDirectInstantiation(): void
{
    new FinalClassWithStringBased();
}
