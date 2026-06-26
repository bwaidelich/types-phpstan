<?php

declare(strict_types=1);

namespace Wwwision\TypesPhpStan\Tests\Data;

use Wwwision\Types\Schema\Dynamic\DynamicRecord;

use function PHPStan\Testing\assertType;

function consume(DynamicRecord $record): void
{
    assertType('mixed', $record->latitude);
    assertType('mixed', $record->anyOtherPropertyName);
}
